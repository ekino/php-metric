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

foreach(array( 1 => 'XHPROF_FLAGS_NO_BUILTINS', 2 => 'XHPROF_FLAGS_CPU', 4 => 'XHPROF_FLAGS_MEMORY') as $value => $name) {
    if (!defined($name)) {
        define($name, $value);
    }
}

class XhprofSample
{
    const FLAGS_NO_BUILTINS = XHPROF_FLAGS_NO_BUILTINS;
    const FLAGS_CPU         = XHPROF_FLAGS_CPU;
    const FLAGS_MEMORY      = XHPROF_FLAGS_MEMORY;

    protected $flag;

    protected $ignoreFunctions;

    protected $data;

    protected $stats;

    /**
     * @param integer $flag
     * @param array   $ignoreFunction
     */
    public function __construct($flag = 0, array $ignoreFunction = array())
    {
        $this->flag = $flag;
        $this->ignoreFunctions = $ignoreFunction;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return integer
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @return array
     */
    public function getIgnoreFunctions()
    {
        return $this->ignoreFunctions;
    }

    protected function buildStats()
    {
        if (is_array($this->stats)) {
            return;
        }

        foreach ($this->data as $name => $data) {
            $sample = XhprofStat::createFromData($name, $data);

            $this->stats[$sample->getKey()] = $sample;
        }
    }

    /**
     * @param string $name
     *
     * @return XhprofStat
     */
    public function getStat($name = 'main()')
    {
        $this->buildStats();

        return isset($this->stats[$name]) ? $this->stats[$name] : null;
    }
}