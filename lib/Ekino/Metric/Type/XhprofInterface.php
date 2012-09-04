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

interface XhprofInterface extends MetricInterface
{
    /**
     * @return XhprofSample
     */
    function getSample();
}