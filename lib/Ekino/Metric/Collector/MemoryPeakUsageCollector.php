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

use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Type\Gauge;

/**
 * Returns the peak of memory, in bytes, that's been allocated to your PHP script.
 */
class MemoryPeakUsageCollector implements CollectorInterface
{
    protected $real;

    protected $prefix;

    /**
     * @param string  $prefix
     * @param boolean $real Set this to true to get the real size of memory allocated from
     *                      system. If not set or false only the memory used by
     *                      emalloc() is reported.
     */
    public function __construct($prefix, $real)
    {
        $this->real = $real;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return new Gauge($this->prefix.($this->real ? 'system' : 'emalloc'), memory_get_peak_usage($this->real));
    }
}