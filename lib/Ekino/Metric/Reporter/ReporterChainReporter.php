<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Reporter;

use Ekino\Metric\Type\TimerInterface;
use Ekino\Metric\Type\GaugeInterface;
use Ekino\Metric\Type\MetricInterface;
use Ekino\Metric\Writer\WriterInterface;
use Ekino\Metric\Exception\UnsupportedException;
use Ekino\Metric\Reporter\NewRelic\NewRelicInteractorInterface;

class ReporterChainReporter implements ReporterInterface
{
    protected $reporters;

    /**
     * @param array $reporters
     */
    public function __construct(array $reporters)
    {
        $this->reporters = $reporters;
    }

    /**
     * {@inheritdoc}
     */
    public function send(array $metrics)
    {
        foreach ($this->reporters as $reporter) {
            $reporter->send($metrics);
        }
    }
}