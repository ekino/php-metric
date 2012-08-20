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

use Ekino\Metric\StringHelper;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testconvertDot()
    {
        $sh = new StringHelper;

        $this->assertEquals('WebProfiler::foo-bar-twig', $sh->convertDot('WebProfiler::foo.bar.twig'));
    }

    /**
     * @dataProvider data
     */
    public function testSanitize($value, $expected)
    {
        $sh = new StringHelper;
        $this->assertEquals($expected, $sh->sanitize($value));
    }

    static public function data()
    {
        return array(
            array('Simple message', 'simple_message'),
            array('WebProfiler::foo.bar.twig', 'webprofiler__foo.bar.twig'),
            array('WebProfiler::foo-bar-twig', 'webprofiler__foo-bar-twig'),
        );
    }
}