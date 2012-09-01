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
use Ekino\Metric\Exception\UnsuppportedException;
use Ekino\Metric\Reporter\NewRelic\NewRelicInteractorInterface;

/**
 * Reference : https://newrelic.com/docs/php/php-custom-metric-collection.html
 *
 */
class NewRelicReporter implements ReporterInterface
{
    protected $interactor;

    /**
     * @param NewRelicInteractorInterface $interactor
     */
    public function __construct(NewRelicInteractorInterface $interactor)
    {
        $this->interactor = $interactor;
    }

    /**
     * @param GaugeInterface $metric
     * @param integer        $timestamp
     *
     * @return void
     */
    protected function sendGauge(GaugeInterface $metric, $timestamp)
    {
        $this->interactor->addCustomMetric($metric->getName(), $metric->getValue());
    }

    /**
     * @param TimerInterface $metric
     * @param integer        $timestamp
     *
     * @return void
     */
    protected function sendTimer(TimerInterface $metric, $timestamp)
    {
        $this->interactor->addCustomMetric($metric->getName(), $metric->getValue());
    }

    /**
     * @param array $metrics
     *
     * @return void
     */
    public function send(array $metrics)
    {
        foreach ($metrics as $data) {
            list($metric, $timestamp) = $data;

            if ($metric instanceof TimerInterface) {
                $this->sendTimer($metric, $timestamp);
            } elseif ($metric instanceof GaugeInterface) {
                $this->sendGauge($metric, $timestamp);
            } else {
                throw new \RuntimeException('Metric not implement');
            }
        }
    }
}