<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Test\Collector;

use Ekino\Metric\Collector\DoctrineCollector;
use Ekino\Metric\StringHelper;
use Doctrine\DBAL\Logging\DebugStack;

class DoctrineCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $stack = new DebugStack();

        $collector = new DoctrineCollector(array(), new StringHelper(), 'php.doctrine.');
        $collector->addLogger('debug', $stack);

        $this->assertEquals(0, count($collector->get()));
    }

    /**
     * @dataProvider dataQuery
     */
    public function testQuery($query, $time, $count)
    {
        $stack = new DebugStack();
        $stack->startQuery($query);
        usleep(2000);
        $stack->stopQuery();

        $collector = new DoctrineCollector(array(), new StringHelper(), 'php.doctrine.');
        $collector->addLogger('debug', $stack);

        $this->assertEquals(2, count($collection = $collector->get()));

        $this->assertEquals($time, $collection[0]->getName());
        $this->assertEquals($count, $collection[1]->getName());
    }

    public function dataQuery()
    {
        return array(
            // SELECT
            array("SELECT\tid FROM FOO",            'php.doctrine.time.debug.select.foo', 'php.doctrine.count.debug.select.foo'),
            array("SELECT\n\n * \nFROM FOO ",       'php.doctrine.time.debug.select.foo', 'php.doctrine.count.debug.select.foo'),
            array("SELECT *   FROM FOO ",           'php.doctrine.time.debug.select.foo', 'php.doctrine.count.debug.select.foo'),
            array("SELECT * FROM FOO as foobar",    'php.doctrine.time.debug.select.foo', 'php.doctrine.count.debug.select.foo'),
            array("SELECT * FROM FOO as FROMbar",   'php.doctrine.time.debug.select.foo', 'php.doctrine.count.debug.select.foo'),
            array("SELECT t0.enabled AS enabled1, t0.name AS name2, t0.relative_path AS relative_path3, t0.host AS host4, t0.enabled_from AS enabled_from5, t0.enabled_to AS enabled_to6, t0.is_default AS is_default7, t0.created_at AS created_at8, t0.updated_at AS updated_at9, t0.locale AS locale10, t0.title AS title11, t0.meta_keywords AS meta_keywords12, t0.meta_description AS meta_description13, t0.id AS id14 FROM page__site t0 WHERE t0.host IN (?) AND t0.enabled = ?", 'php.doctrine.time.debug.select.page__site', 'php.doctrine.count.debug.select.page__site'),

            // UPDATE
            array("UPDATE\t tablename\n SET update=1", 'php.doctrine.time.debug.update.tablename', 'php.doctrine.count.debug.update.tablename'),

            // INSERT
            array("INSERT \tINTO tablename SET update=1", 'php.doctrine.time.debug.insert.tablename', 'php.doctrine.count.debug.insert.tablename'),
            array("INSERT \tINTO \ntablename SET update=1", 'php.doctrine.time.debug.insert.tablename', 'php.doctrine.count.debug.insert.tablename'),

            // DELETE
            array("DELETE\tFROM FOO ", 'php.doctrine.time.debug.delete.foo', 'php.doctrine.count.debug.delete.foo'),
            array("DELETE FROM\n\t FOO ", 'php.doctrine.time.debug.delete.foo', 'php.doctrine.count.debug.delete.foo'),
        );
    }
}