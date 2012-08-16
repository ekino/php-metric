<?php

namespace Ekino\Metric\Collector;

use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Type\Gauge;

/**
 * Returns the peak of memory, in bytes, that's been allocated to your PHP script.
 */
class MemoryPeakUsageCollector implements CollectorInterface
{
    protected $real;

    /**
     * @param boolean $real Set this to true to get the real size of memory allocated from
     *                      system. If not set or false only the memory used by
     *                      emalloc() is reported.
     */
    public function __construct($real)
    {
        $this->real = $real;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return new Gauge('php.memory.peak.'.($this->real ? 'system' : 'emalloc'), memory_get_peak_usage($this->real));
    }
}