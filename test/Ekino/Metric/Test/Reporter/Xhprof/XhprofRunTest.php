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

use Ekino\Metric\Reporter\Xhprof\XhprofRun;
use Ekino\Metric\Reporter\Xhprof\XhprofSample;

class XhprofRunTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!function_exists('xhprof_enable')) {
            $this->markTestSkipped('The XHProf extension is not available');
        }

        // reset the XhprofRun
        try {
            XhprofRun::stop();
        } catch (\RuntimeException $e) {

        }
    }

    public function testStandardExecution()
    {
        XhprofRun::start(XhprofSample::FLAGS_CPU + XhprofSample::FLAGS_MEMORY);
        $this->foobar();
        $sample = XhprofRun::stop();

        $this->assertInstanceOf('Ekino\Metric\Reporter\Xhprof\XhprofSample', $sample);
    }

    public function foobar()
    {
        $u = 0;
        foreach (range(1, 12) as $i) {
            $u += $i;
            $date = new \Datetime;
            $date->format('U');
        }
    }
}