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
 * A gauge is an instantaneous measurement of a value
 */
class Gauge implements GaugeInterface
{
    protected $name;

    protected $value;

    /**
     * @param string $name
     * @param float $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = (float) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}