<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test\Type;

use Ekino\Metric\Type\DefinedTimer;

class DefinedTimerTest extends \PHPUnit_Framework_TestCase
{
    public function testTimerAutoStart()
    {
        $t = new DefinedTimer('execution.timer.process1', 12);

        $this->assertEquals(12, $t->tick());
        $this->assertEquals(12, $t->getValue());
    }
}