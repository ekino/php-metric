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

use Ekino\Metric\Reporter\NewRelicReporter;
use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Type\Gauge;
use Ekino\Metric\Type\TimerInterface;
use Ekino\Metric\Writer\InMemoryWriter;

class NewRelicReporterTest extends \PHPUnit_Framework_TestCase
{
    public function testGaugeManager()
    {
        $interactor = $this->getMock('Ekino\Metric\Reporter\NewRelic\NewRelicInteractorInterface');
        $interactor->expects($this->once())->method('addCustomMetric');

        $newrelic = new NewRelicReporter($interactor);

        $newrelic->send(array(
            array(new Gauge('mail.index', 12), 1345136728)
        ));
    }

    public function testTimerManager()
    {
        $timer = $this->getMock('Ekino\Metric\Type\TimerInterface');

        $interactor = $this->getMock('Ekino\Metric\Reporter\NewRelic\NewRelicInteractorInterface');
        $interactor->expects($this->once())->method('addCustomMetric');

        $newrelic = new NewRelicReporter($interactor);

        $newrelic->send(array(
            array($timer, 1345136728)
        ));
    }

    public function testCombinedMetrics()
    {
        $timer = $this->getMock('Ekino\Metric\Type\TimerInterface');

        $interactor = $this->getMock('Ekino\Metric\Reporter\NewRelic\NewRelicInteractorInterface');
        $interactor->expects($this->exactly(2))->method('addCustomMetric');

        $newrelic = new NewRelicReporter($interactor);

        $newrelic->send(array(
            array(new Gauge('mail.index', 12), 1345136728),
            array($timer, 1345136728),
        ));
    }
}