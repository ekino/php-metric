<?php

namespace Ekino\Metric\Test\Collector;

use Ekino\Metric\Collector\TimerFunctionCollector;

class TimerFunctionCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidCallback()
    {
        $collector = new TimerFunctionCollector('function.heavy_pdf', function() {
            usleep(200000);
        });

        $collector->run();

        $timer = $collector->get();

        $this->assertInstanceOf('Ekino\Metric\Type\TimerInterface', $timer);
    }

    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage The callback is not valid
     */
    public function testInvalidCallback()
    {
        $collector = new TimerFunctionCollector('function.heavy_pdf', false);
    }

    /**
     * @expectedException        RuntimeException
     * @expectedExceptionMessage No callback provided
     */
    public function testNoCallback()
    {
        $collector = new TimerFunctionCollector('function.heavy_pdf');
        $collector->run();
    }
}