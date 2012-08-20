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

class UdpWriter implements WriterInterface
{
    protected $fp;

    protected $host;

    protected $port;

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        if (is_resource($this->fp)) {
            return;
        }

        $this->fp = fsockopen(sprintf('udp://%s', $this->host), $this->port);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (!is_resource($this->fp)) {
            return;
        }

        fclose($this->fp);
    }

    /**
     * @param string $host
     * @param string $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        if (!is_resource($this->fp)) {
            return;
        }

        fwrite($this->fp, $data);
    }
}
