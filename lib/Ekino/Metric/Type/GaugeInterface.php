<?php

namespace Ekino\Metric\Type;

interface GaugeInterface extends MetricInterface
{
    /**
     * @return float
     */
    function getValue();
}