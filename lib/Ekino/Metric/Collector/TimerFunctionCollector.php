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

use Ekino\Metric\Type\Timer;

/**
 * Returns the amount of memory, in bytes, that's currently being allocated to your PHP script.
 */
class TimerFunctionCollector implements CollectorInterface
{
    protected $callback;

    protected $name;

    protected $timer;

    /**
     * @param function $callback
     */
    public function __construct($name, $callback = null)
    {
        if ($callback !== null) {
            $this->setCallback($callback);
        }

        $this->name = $name;
    }

    /**
     * @param function $callback
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \RuntimeException('The callback is not valid');
        }

        $this->callback = $callback;
    }

    /**
     * Execute the callback function
     */
    public function run()
    {
        $this->timer = new Timer($this->name, true);

        if (!is_callable($this->callback)) {
            throw new \RuntimeException('No callback provided');
        }

        call_user_func($this->callback);

        $this->timer->tick();
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->timer;
    }
}