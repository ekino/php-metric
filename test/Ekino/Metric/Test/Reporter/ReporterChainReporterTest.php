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

use Ekino\Metric\Reporter\ReporterChainReporter;
use Ekino\Metric\Reporter\ReporterInterface;
use Ekino\Metric\Type\MetricInterface;

class ReporterChainReporterTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $reporter1 = $this->getMock('Ekino\Metric\Reporter\ReporterInterface');
        $reporter1->expects($this->once())->method('send');

        $reporter2 = $this->getMock('Ekino\Metric\Reporter\ReporterInterface');
        $reporter2->expects($this->once())->method('send');

        $chain = new ReporterChainReporter(array(
            $reporter1,
            $reporter2
        ));

        $chain->send(array(
            array($this->getMock('Ekino\Metric\Type\MetricInterface'), 123123),
            array($this->getMock('Ekino\Metric\Type\MetricInterface'), 123124)
        ));
    }
}