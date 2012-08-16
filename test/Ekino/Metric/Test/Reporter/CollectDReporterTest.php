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

use Ekino\Metric\Reporter\CollectDReporter;
use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Type\Gauge;
use Ekino\Metric\Type\TimerInterface;
use Ekino\Metric\Writer\InMemoryWriter;

class MetricManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $collectd;

    protected $writer;

    public function setUp()
    {
        $this->writer = new InMemoryWriter;

        $this->collectd = new CollectDReporter('web1-php-metric', $this->writer);
    }

    public function testGaugeManager()
    {
        $this->collectd->send(array(
            array(new Gauge('mail.index', 12), 1345136728)
        ));

        $expected = '0001000c00000000502d285800000014776562312d7068702d6d6574726963000002000f6d61696c2e696e646578000004000a6761756765000006000f0001010000000000002840';

        $this->assertEquals($expected, bin2hex($this->writer->getData()));
    }

    public function testTimerManager()
    {
        $timer = $this->getMock('Ekino\Metric\Type\TimerInterface');
        $timer->expects($this->once())->method('getName')->will($this->returnValue('request.route.sonata_dispatch'));
        $timer->expects($this->once())->method('getValue')->will($this->returnValue(10));

        $this->collectd->send(array(
            array($timer, 1345136728)
        ));

        $expected = '0001000c00000000502d285800000014776562312d7068702d6d65747269630000020022726571756573742e726f7574652e736f6e6174615f6469737061746368000004000a6761756765000006000f0001010000000000002440';

        $this->assertEquals($expected, bin2hex($this->writer->getData()));
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

        $expected = '0001000c00000000502d285800000014776562312d7068702d6d6574726963000002000f6d61696c2e696e646578000004000a6761756765000006000f0001010000000000002840';
        $expected .= '0001000c00000000502d285800000014776562312d7068702d6d65747269630000020022726571756573742e726f7574652e736f6e6174615f6469737061746368000004000a6761756765000006000f0001010000000000002440';

        $this->assertEquals($expected, bin2hex($this->writer->getData()));
    }
}