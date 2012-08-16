<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Type;

class Timer implements TimerInterface
{
    protected $name;

    protected $startedAt;

    protected $tickAt;

    /**
     * @param string $name
     * @param bool   $autostart
     */
    public function __construct($name, $autostart)
    {
        $this->name      = $name;
        $this->startedAt = null;
        $this->tickAt    = null;

        if ($autostart === true) {
            $this->start();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $this->startedAt = microtime(true);
    }

    /**
     * {@inheritdoc}
     */
    public function tick()
    {
        if ($this->startedAt === null) {
            throw new \RuntimeException('The timer is not initialized (startedAt)');
        }

        $this->tickAt = microtime(true);

        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        if ($this->tickAt === null || $this->startedAt === null) {
            throw new \RuntimeException('The timer is not initialized (startedAt or tickedAt)');
        }

        return $this->tickAt - $this->startedAt;
    }
}