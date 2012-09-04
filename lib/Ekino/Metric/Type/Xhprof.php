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

use Ekino\Metric\Reporter\Xhprof\XhprofSample;

class Xhprof implements XhprofInterface
{
    protected $name;

    protected $sample;

    /**
     * @param string       $name
     * @param XhprofSample $sample
     */
    public function __construct($name, XhprofSample $sample)
    {
        $this->name = $name;
        $this->sample = $sample;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return XhprofSample
     */
    public function getSample()
    {
        return $this->sample;
    }
}