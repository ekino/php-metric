<?php

namespace Ekino\Metric\Collector;

use Ekino\Metric\Type\MetricInterface;

interface CollectorInterface
{
    /**
     * @return MetricInterface
     */
    public function get();
}
