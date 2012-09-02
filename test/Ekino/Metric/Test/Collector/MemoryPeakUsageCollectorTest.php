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

use Ekino\Metric\Collector\MemoryPeakUsageCollector;

class MemoryPeakUsageCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testMemoryUsageSystem()
    {
        $g = new MemoryPeakUsageCollector('php.memory.peak.', true);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.peak.system', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

    public function testMemoryUsageEmalloc()
    {
        $g = new MemoryPeakUsageCollector('php.memory.peak.', false);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.peak.emalloc', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }
}