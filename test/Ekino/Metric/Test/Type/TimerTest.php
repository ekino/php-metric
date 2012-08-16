<?php

namespace Ekino\Metric\Test\Type;

use Ekino\Metric\Type\Timer;

class TimerTest extends \PHPUnit_Framework_TestCase
{
    public function testTimerAutoStart()
    {
        $t = new Timer('execution.timer.process1', true);

        $this->assertNotNull($t->tick());
    }

    public function testTimerNoAutoStart()
    {
        $t = new Timer('execution.timer.process1', false);

        $t->start();

        usleep(200000);

        $this->assertNotNull($t->tick());

        $this->assertTrue($t->getValue() > 0.2);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testTimerNoAutoStartGetValue()
    {
        $t = new Timer('execution.timer.process1', false);

        $t->getValue();
    }
}