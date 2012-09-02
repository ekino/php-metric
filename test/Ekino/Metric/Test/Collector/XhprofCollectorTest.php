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

use Ekino\Metric\Collector\XhprofCollector;
use Ekino\Metric\Type\Collection;

class XhprofCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!function_exists('xhprof_enable')) {
            $this->markTestSkipped('The XHProf extension is not available');
        }
    }

    public function testGet()
    {
        $collector = new XhprofCollector('php.', array('main' => 'main()'));

        $collector->start();
        $collector->stop();

        $collection = $collector->get();

        $this->assertInstanceOf('Ekino\Metric\Type\Collection', $collection);
    }
}