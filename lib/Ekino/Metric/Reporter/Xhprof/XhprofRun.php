<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Reporter\Xhprof;

class XhprofRun
{
    /**
     * @var XhprofSample
     */
    static protected $sample;

    /**
     * @static
     *
     * @param integer $flag
     * @param array   $ignoreFunctions
     *
     * @throws \RuntimeException
     */
    static function start($flag = 0, array $ignoreFunctions = array())
    {
        if (self::$sample) {
            throw new \RuntimeException('The profiler has been started');
        }

        self::$sample = new XhprofSample($flag, $ignoreFunctions);

        xhprof_enable($flag, $ignoreFunctions);
    }

    /**
     * Stop the Xhprof runs
     *
     * @static
     *
     * @return XhprofSample
     *
     * @throws \RuntimeException
     */
    static public function stop()
    {
        $sample = self::$sample;

        self::$sample = null;

        if (!$sample instanceof XhprofSample) {
            throw new \RuntimeException('The profiler has not been started');
        }

        $sample->setData(xhprof_disable());

        return $sample;
    }
}