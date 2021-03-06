<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test\Type;

use Ekino\Metric\Type\Gauge;

class GaugeTest extends \PHPUnit_Framework_TestCase
{
    public function testGauge()
    {
        $g = new Gauge('cpu.usage', 1);

        $this->assertEquals(1, $g->getValue());
        $this->assertEquals('cpu.usage', $g->getName());
    }

    public function testGaugeNonInteger()
    {
        $g = new Gauge('cpu.usage', 'foo');

        $this->assertEquals(0, $g->getValue());
        $this->assertEquals('cpu.usage', $g->getName());
    }
}