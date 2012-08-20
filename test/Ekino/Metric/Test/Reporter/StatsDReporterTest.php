<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test\Reporter;

use Ekino\Metric\Reporter\StatsDReporter;
use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Type\Gauge;
use Ekino\Metric\Type\TimerInterface;
use Ekino\Metric\Writer\InMemoryWriter;

class StatsDReporterTest extends \PHPUnit_Framework_TestCase
{
    protected $collectd;

    protected $writer;

    public function setUp()
    {
        $this->writer = new InMemoryWriter;

//        $this->writer = new \Ekino\Metric\Writer\UdpWriter('localhost', 25826);

        $this->collectd = new StatsDReporter($this->writer);
    }

    public function testGaugeManager()
    {
        $this->collectd->send(array(
            array(new Gauge('mail.index', 12), 1345136728)
        ));

        $this->assertEquals('mail.index:12.000000|g', $this->writer->getData());
    }

    public function testTimerManager()
    {
        $timer = $this->getMock('Ekino\Metric\Type\TimerInterface');
        $timer->expects($this->once())->method('getName')->will($this->returnValue('request.route.sonata_dispatch'));
        $timer->expects($this->once())->method('getValue')->will($this->returnValue(10));

        $this->collectd->send(array(
            array($timer, 1345136728)
        ));

        $this->assertEquals('request.route.sonata_dispatch:10.000000|s', $this->writer->getData());
    }

    public function testCombinedMetrics()
    {
        $timer = $this->getMock('Ekino\Metric\Type\TimerInterface');
        $timer->expects($this->once())->method('getName')->will($this->returnValue('request.route.sonata_dispatch'));
        $timer->expects($this->once())->method('getValue')->will($this->returnValue(10));

        $this->collectd->send(array(
            array(new Gauge('mail.index', 12), 1345136728),
            array($timer, 1345136728),
        ));

        $expected = 'mail.index:12.000000|grequest.route.sonata_dispatch:10.000000|s';

        $this->assertEquals($expected, $this->writer->getData());
    }
}