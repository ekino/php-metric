<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test;

use Ekino\Metric\MetricManager;

class MetricManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testManager()
    {
        $reporter = $this->getMock('Ekino\Metric\Reporter\ReporterInterface');
        $reporter->expects($this->once())->method('send');

        $m = new MetricManager($reporter);

        $metric = $this->getMock('Ekino\Metric\Type\MetricInterface');

        $m->add($metric);

        $m->flush();
    }
}