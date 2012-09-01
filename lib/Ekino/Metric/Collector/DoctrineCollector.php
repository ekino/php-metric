<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Collector;

use Doctrine\DBAL\Logging\DebugStack;
use Ekino\Metric\Collector\CollectorInterface;
use Ekino\Metric\Exception\UnsuppportedException;
use Ekino\Metric\Type\DefinedTimer;
use Ekino\Metric\Type\Gauge;
use Ekino\Metric\StringHelper;
use Ekino\Metric\Type\Collection;

class DoctrineCollector implements CollectionCollectorInterface
{
    protected $loggers;

    protected $prefix;

    protected $stringHelper;

    /**
     * @param array        $loggers
     * @param StringHelper $stringHelper
     * @param string       $prefix
     */
    public function __construct(array $loggers, StringHelper $stringHelper, $prefix)
    {
        foreach ($loggers as $name => $logger) {
            $this->addLogger($name, $logger);
        }

        $this->prefix       = $prefix;
        $this->stringHelper = $stringHelper;
    }

    /**
     * @param string     $name
     * @param DebugStack $stack
     */
    public function addLogger($name, DebugStack $stack)
    {
        $this->loggers[$name] = $stack;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $collection = new Collection;

        foreach ($this->loggers as $connection => $logger) {
            $stats = array();

            foreach ($logger->queries as $query) {
                list($type, $table) = $this->getQueryInfos($query['sql']);

                $code = $connection.'.'.$type.'.'.$table;

                $collection->add(new DefinedTimer(
                    $this->stringHelper->sanitize(sprintf('%s.time.%s', $this->prefix, $code)),
                    $query['executionMS']
                ));

                if (!isset($stats[$code])) {
                    $stats[$code] = 0;
                }

                $stats[$code]++;
            }

            foreach ($stats as $code => $counter) {
                $collection->add(new Gauge(
                    $this->stringHelper->sanitize(sprintf('%s.count.%s', $this->prefix, $code)),
                    $counter
                ));
            }
        }

        return $collection;
    }

    /**
     * These patterns will match most of the doctrine generated queries
     *
     * @param string $query
     *
     * @return array
     */
    protected function getQueryInfos($query)
    {
        $type = strtolower(substr($query, 0, 6));

        switch ($type) {
            case 'select':
                $pattern = '/SELECT\s((.|\n)*)\sFROM\s(?P<table>[a-zA-Z_]*)($|(.*)$)/i';
                break;

            case 'insert':
                $pattern = '/INSERT(\s+)INTO(\s+)(?P<table>[a-zA-Z_]*)(\s*)(.*)/i';
                break;

            case 'update':
                $pattern = '/UPDATE(\s+)(?P<table>[a-zA-Z_]*)(\s*)(.*)/i';
                break;

            case 'delete':
                $pattern = '/DELETE(\s+)FROM(\s+)(?P<table>[a-zA-Z_]*)(\s*)(.*)/i';
                break;

            default:
                $type = 'other';
                $pattern = false;
        }

        if ($pattern && preg_match($pattern, $query, $matches)) {
            return array($type, $matches['table']);
        }

        return array($type, '__unknown');
    }
}