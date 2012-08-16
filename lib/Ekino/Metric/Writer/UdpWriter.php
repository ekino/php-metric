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

    protected $host;

    protected $port;

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
        $fp = fsockopen(sprintf('udp://%s', $this->host), $this->port);

        if (!$fp) {
            return;
        }

        fwrite($fp, $data);
        fclose($fp);
    }
}
