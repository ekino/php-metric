<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Writer;

interface WriterInterface
{

    /**
     * Open Stream
     *
     * @return mixed
     */
    function open();

    /**
     * Write data to stream
     *
     * @param $data
     *
     * @return void
     */
    function write($data);

    /**
     * Close Stream
     *
     * @return mixed
     */
    function close();
}
