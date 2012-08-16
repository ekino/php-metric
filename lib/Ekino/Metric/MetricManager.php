<?php

namespace Ekino\Metric;

use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Reporter\ReporterInterface;

/**
 * This class collect all metrics from different collector and
 * publish them when the flush method is called
 */
class MetricManager
{
    protected $metrics;

    protected $reporter;

    /**
     * @param Collector\ReporterInterface $reporter
     */
    public function __construct(ReporterInterface $reporter)
    {
        $this->metrics = array();
        $this->reporter = $reporter;
    }

    /**
     * Collect a metric
     *
     * @param Type\MetricInterface $metric
     */
    public function add(MetricInterface $metric)
    {
        $this->metrics[] = $metric;
    }

    /**
     * Send the collected metric to the dedicated reporter
     *
     * @return void
     */
    public function flush()
    {
        $this->reporter->send($this->metrics);

        $this->metrics = array();
    }
}