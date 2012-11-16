<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric;

class StringHelper
{
    /**
     * Sanitize a string by
     *   - removing replacing all non alphanumeric and dot characters by `_`
     *   - put the string in lowercase
     *
     * @param string $string
     */
    public function sanitize($string)
    {
        return strtolower(preg_replace("/[^A-Za-z0-9.-]/", '_', $string));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function convertDot($string)
    {
        return strtr($string, '.', '-');
    }
}