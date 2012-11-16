<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Reporter;

use Ekino\Metric\Type\TimerInterface;
use Ekino\Metric\Type\GaugeInterface;
use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Writer\WriterInterface;
use Ekino\Metric\Exception\UnsupportedException;

/**
 * Reference : https://github.com/etsy/statsd
 *
 */
class StatsDReporter implements ReporterInterface
{
    protected $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param GaugeInterface $metric
     * @param integer        $timestamp
     *
     * @return string
     */
    protected function buildGauge(GaugeInterface $metric, $timestamp)
    {
        return sprintf("%s:%f|%s", $metric->getName(), $metric->getValue(), "g");
    }

    /**
     * @param TimerInterface $metric
     * @param integer        $timestamp
     *
     * @return string
     */
    protected function buildTimer(TimerInterface $metric, $timestamp)
    {
        return sprintf("%s:%f|%s", $metric->getName(), $metric->getValue(), "ms");
    }

    /**
     * {@inheritdoc}
     */
    public function send(array $metrics)
    {
        $datas = array();
        foreach ($metrics as $data) {
            list($metric, $timestamp) = $data;

            try {
                if ($metric instanceof TimerInterface) {
                    $datas[] = $this->buildTimer($metric, $timestamp);
                } elseif ($metric instanceof GaugeInterface) {
                    $datas[] = $this->buildGauge($metric, $timestamp);
                }
            } catch (UnsupportedException $e) {

            }
        }

        if (count($datas) > 0) {
            $this->writer->open();
            foreach ($datas as $data) {
                $this->writer->write($data);
            }
            $this->writer->close();
        }
    }
}