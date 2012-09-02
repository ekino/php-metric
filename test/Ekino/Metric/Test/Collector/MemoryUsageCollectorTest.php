<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test\Collector;

use Ekino\Metric\Collector\MemoryUsageCollector;

class MemoryUsageCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testMemoryUsageSystem()
    {
        $g = new MemoryUsageCollector('php.memory.usage.', true);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.usage.system', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

    public function testMemoryUsageEmalloc()
    {
        $g = new MemoryUsageCollector('php.memory.usage.', false);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.usage.emalloc', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

}