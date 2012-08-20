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

class InMemoryWriter implements WriterInterface
{
    protected $data;

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $this->data = '';
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {}

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        $this->data .= $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
