<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @return integer the different between start and the current tick in ms
     */
    function tick();

    /**
     * @return integer the different between start and the last tick in ms
     */
    function getValue();
}