<?php

namespace Ekino\Metric\Reporter;

interface ReporterInterface
{
    /**
     * @param array $metrics
     *
     * @return void
     */
    public function send(array $metrics);
}
