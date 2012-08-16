<?php

namespace Ekino\Metric\Test\Collector;

use Ekino\Metric\Collector\MemoryUsageCollector;

class MemoryUsageCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testMemoryUsageSystem()
    {
        $g = new MemoryUsageCollector(true);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.usage.system', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

    public function testMemoryUsageEmalloc()
    {
        $g = new MemoryUsageCollector(false);

        $gauge = $g->get();

        $this->assertInstanceOf('Ekino\Metric\Type\GaugeInterface', $gauge);
        $this->assertEquals('php.memory.usage.emalloc', $gauge->getName());
        $this->assertNotNull($gauge->getValue());
    }

}