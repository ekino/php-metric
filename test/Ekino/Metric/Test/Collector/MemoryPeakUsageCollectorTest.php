<?php

namespace Ekino\Metric\Test\Collector;

use Ekino\Metric\Collector\MemoryPeakUsageCollector;

class MemoryPeakUsageCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testMemoryUsageSystem()
    {
        $g = new MemoryPeakUsageCollector(true);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.peak.system', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

    public function testMemoryUsageEmalloc()
    {
        $g = new MemoryPeakUsageCollector(false);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.peak.emalloc', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

}