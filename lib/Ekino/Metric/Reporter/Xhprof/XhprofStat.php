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

class XhprofStat
{
    const TYPE_CALL               = 'ct';   // number of calls to bar() from foo()
    const TYPE_TIME               = 'wt';   // time in bar() when called from foo()
    const TYPE_CPU                = 'cpu';  // cpu time in bar() when called from foo()
    const TYPE_MEMORY_USAGE       = 'mu';   // change in PHP memory usage in bar() when called from foo()
    const TYPE_PEAK_MEMORY_USAGE  = 'pmu';  // change in PHP peak memory usage in bar() when called from foo()

    protected $from;

    protected $name;

    protected $call;

    protected $time;

    protected $cpu;

    protected $memoryUsage;

    protected $peakMemoryUsage;

    /**
     * @param integer $key
     * @param integer $call
     * @param integer $time
     * @param integer $cpu
     * @param integer $memoryUsage
     * @param integer $peakMemoryUsage
     */
    private function __construct($key, $call, $time, $cpu, $memoryUsage, $peakMemoryUsage)
    {
        if ($key !== 'main()') {
            list($this->from, $this->name) = explode('==>', $key);
        }

        $this->call = $call;
        $this->time = $time;
        $this->cpu = $cpu;
        $this->memoryUsage = $memoryUsage;
        $this->peakMemoryUsage = $peakMemoryUsage;
    }

    /**
     * @param string $name
     * @param array  $data
     *
     * @return XhprofStat
     */
    static function createFromData($key, array $data)
    {
        return new self(
            $key,
            isset($data[self::TYPE_CALL]) ? $data[self::TYPE_CALL] : null,
            isset($data[self::TYPE_TIME]) ? $data[self::TYPE_TIME] : null,
            isset($data[self::TYPE_CPU]) ? $data[self::TYPE_CPU] : null,
            isset($data[self::TYPE_MEMORY_USAGE]) ? $data[self::TYPE_MEMORY_USAGE] : null,
            isset($data[self::TYPE_PEAK_MEMORY_USAGE]) ? $data[self::TYPE_PEAK_MEMORY_USAGE] : null
        );
    }

    /**
     * @return string
     */
    public function getKey()
    {
        if ($this->from && $this->name) {
            return $this->from.'==>'.$this->name;
        }

        return 'main()';
    }

    /**
     * @return integer
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @return integer
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @return integer
     */
    public function getPeakMemoryUsage()
    {
        return $this->peakMemoryUsage;
    }

    /**
     * @return integer
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @return integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }
}