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