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

use Ekino\Metric\Reporter\Xhprof\XhprofSample;

class XhprofSampleTest extends \PHPUnit_Framework_TestCase
{
    protected $sample;

    public function testGetValue()
    {
        $stat = $this->sample->getStat('main()');

        $this->assertInstanceOf('Ekino\Metric\Reporter\Xhprof\XhprofStat', $stat);

        $this->assertEquals(11128, $stat->getMemoryUsage());
        $this->assertEquals(0, $stat->getPeakMemoryUsage());
        $this->assertEquals(1, $stat->getCall());
        $this->assertEquals(541, $stat->getTime());
    }

    public function setUp()
    {
        if (!function_exists('xhprof_enable')) {
            $this->markTestSkipped('The XHProf extension is not available');
        }

        $this->sample = new XhprofSample;
        $this->sample->setData(array(
             'Ekino\\Metric\\Test\\Reporter\\XhprofRunTest::foobar==>range' => array (
               'ct' => 1,
               'wt' => 14,
               'cpu' => 0,
               'mu' => 3808,
               'pmu' => 0,
             ),
             'Ekino\\Metric\\Test\\Reporter\\XhprofRunTest::foobar==>DateTime::__construct' => array (
               'ct' => 12,
               'wt' => 235,
               'cpu' => 0,
               'mu' => 1496,
               'pmu' => 0,
             ),
             'Ekino\\Metric\\Test\\Reporter\\XhprofRunTest::foobar==>DateTime::format' => array (
               'ct' => 12,
               'wt' => 98,
               'cpu' => 0,
               'mu' => 2664,
               'pmu' => 0,
             ),
             'main()==>Ekino\\Metric\\Test\\Reporter\\XhprofRunTest::foobar' => array (
               'ct' => 1,
               'wt' => 479,
               'cpu' => 0,
               'mu' => 6336,
               'pmu' => 0,
             ),
             'Ekino\\Metric\\Reporter\\Xhprof\\XhprofRun::stop==>xhprof_disable' => array (
               'ct' => 1,
               'wt' => 7,
               'cpu' => 0,
               'mu' => 1120,
               'pmu' => 0,
             ),
             'main()==>Ekino\\Metric\\Reporter\\Xhprof\\XhprofRun::stop' => array (
               'ct' => 1,
               'wt' => 24,
               'cpu' => 0,
               'mu' => 2872,
               'pmu' => 0,
             ),
             'main()' => array (
               'ct' => 1,
               'wt' => 541,
               'cpu' => 0,
               'mu' => 11128,
               'pmu' => 0,
             ),
        ));
    }
}