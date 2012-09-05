<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Collector;

use Ekino\Metric\Type\DefinedTimer;
use Ekino\Metric\Type\Gauge;
use Ekino\Metric\Type\Collection;
use Ekino\Metric\Type\Xhprof;
use Ekino\Metric\Reporter\Xhprof\XhprofRun;
use Ekino\Metric\Reporter\Xhprof\XhprofSample;

class XhprofCollector implements CollectionCollectorInterface
{
    protected $flag;

    protected $ignoreFunctions;

    protected $functions;

    /**
     * @var XhprofSample
     */
    protected $sample;

    protected $prefix;

    /**
     * @param string  $prefix
     * @param integer $flag
     * @param array   $ignoreFunction
     */
    public function __construct($prefix, $functions = array(), $flag = 0, array $ignoreFunction = array())
    {
        $this->prefix           = $prefix;
        $this->flag             = $flag;
        $this->ignoreFunctions  = $ignoreFunction;
        $this->functions        = $functions;
    }

    /**
     * Start a xhprof trace
     */
    public function start()
    {
        XhprofRun::start($this->flag, $this->ignoreFunctions);
    }

    /**
     * Stop a xhprof trace
     */
    public function stop()
    {
        $this->sample = XhprofRun::stop();
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $collection = new Collection();

        if (!$this->sample) {
            return $collection;
        }

        foreach ($this->functions as $name => $function) {
            $stat = $this->sample->getStat($function);

            $collection->add(new Xhprof(sprintf("%s%s", $this->prefix, "sample"), $this->sample));

            if (!$stat) {
                return $collection;
            }

            if ($stat->getCall() !== null) {
                $collection->add(new Gauge(sprintf("%s%s.%s", $this->prefix, 'call', $name), $stat->getCall()));
            }

            if ($stat->getCpu() !== null) {
                $collection->add(new Gauge(sprintf("%s%s.%s", $this->prefix, 'cpu', $name), $stat->getCpu()));
            }

            if ($stat->getMemoryUsage() !== null) {
                $collection->add(new Gauge(sprintf("%s%s.%s", $this->prefix, 'memory_usage', $name), $stat->getMemoryUsage()));
            }

            if ($stat->getPeakMemoryUsage() !== null) {
                $collection->add(new Gauge(sprintf("%s%s.%s", $this->prefix, 'peak_memory_usage', $name), $stat->getPeakMemoryUsage()));
            }

            if ($stat->getTime() !== null) {
                $collection->add(new DefinedTimer(sprintf("%s%s.%s", $this->prefix, 'time', $name), $stat->getTime()));
            }
        }

        return $collection;
    }

    /**
     * @return XhprofSample
     */
    public function getSample()
    {
        return $this->sample;
    }
}