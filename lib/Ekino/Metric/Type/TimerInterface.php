<?php

namespace Ekino\Metric\Type;

/**
 * A timer measures the rate that a particular piece of code is called
 *
 */
interface TimerInterface extends MetricInterface
{
    /**
     * @return float
     */
    function start();

    /**
     * @return integer represents milliseconds
     */
    function tick();

    /**
     * @return the last value recorded by the tick function
     */
    function getValue();
}