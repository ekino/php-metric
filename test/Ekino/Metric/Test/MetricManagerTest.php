<?php

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