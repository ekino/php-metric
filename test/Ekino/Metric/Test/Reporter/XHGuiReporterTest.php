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

use Ekino\Metric\Reporter\XHGuiReporter;
use Ekino\Metric\Reporter\XHGui\ParameterResolverInterface;
use Ekino\Metric\Reporter\Xhprof\XhprofSample;
use Ekino\Metric\Type\Xhprof;

class XHGuiReporterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        if (is_file($this->dbFile)) {
            unlink($this->dbFile);
        }
    }

    public function setUp()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('PDO SQLite unavailable');
        }

        $this->dbFile = sys_get_temp_dir().'/metric_foobar.db';

        if (is_file($this->dbFile)) {
            unlink($this->dbFile);
        }

        $this->pdo = new \PDO( 'sqlite:' . $this->dbFile);

        // https://github.com/preinheimer/xhprof/blob/master/xhprof_lib/utils/Db/Pdo.php
        $result = $this->pdo->exec(" CREATE TABLE details (
         id TEXT NOT NULL,
                  `url` varchar(255) default NULL,
         `c_url` varchar(255) default NULL,
         `timestamp` timestamp NOT NULL ,
         `server name` varchar(64) default NULL,
         `perfdata` MEDIUMBLOB,
         `type` tinyint(4) default NULL,
         `cookie` BLOB,
         `post` BLOB,
         `get` BLOB,
         `pmu` int(11) default NULL,
         `wt` int(11) default NULL,
         `cpu` int(11) default NULL,
         `server_id` char(3) NOT NULL default 't11',
         `aggregateCalls_include` varchar(255) DEFAULT NULL,
         PRIMARY KEY  (`id`)
         );");
    }

    public function testSend()
    {
        $resolver = $this->getMock('Ekino\Metric\Reporter\XHGui\ParameterResolverInterface');
        $reporter = new XHGuiReporter('sqlite:' . $this->dbFile, '', '', array(), 'sr1', $resolver);
        $sample = new XhprofSample();
        $sample->setData(array('main()' => array(
            'wt' => 100,
            'cpu' => 123,
            'pmu' => 56
        )));

        $reporter->send(array(
           array(new Xhprof('foobar', $sample), 1345136728)
        ));

        $r = $this->pdo->query('SELECT * FROM details');

        $results = $r->fetchAll();
        $this->assertCount(1, $results);
        $this->assertEquals(100, $results[0]['wt']);
        $this->assertEquals(123, $results[0]['cpu']);
        $this->assertEquals(56, $results[0]['pmu']);

    }
}