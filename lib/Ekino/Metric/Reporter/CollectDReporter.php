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

/**
 * Reference : http://collectd.org/wiki/index.php/Binary_protocol
 *
 */
class CollectDReporter implements ReporterInterface
{
    protected $host;

    protected $writer;

    const PART_TYPE_HOST             = 0x0000;   // String : The name of the host to associate with subsequent data values
    const PART_TYPE_TIME             = 0x0001;   // Numeric : The timestamp to associate with subsequent data values, unix time format (seconds since epoch)
    const PART_TYPE_TIME_HD          = 0x0008;   // Numeric : The timestamp to associate with subsequent data values. Time is defined in 2–30 seconds since epoch. New in Version 5.0.
    const PART_TYPE_PLUGIN           = 0x0002;   // String : The plugin name to associate with subsequent data values, e.g. "cpu"
    const PART_TYPE_PLUGIN_INSTANCE  = 0x0003;   // String : The plugin instance name to associate with subsequent data values, e.g. "1"
    const PART_TYPE_TYPE             = 0x0004;   // String : The type name to associate with subsequent data values, e.g. "cpu"
    const PART_TYPE_TYPE_INSTANCE    = 0x0005;   // String : The type instance name to associate with subsequent data values, e.g. "idle"
    const PART_TYPE_VALUES           = 0x0006;   // other : Data values, see above
    const PART_TYPE_INTERVAL         = 0x0007;   // Numeric : Interval used to set the "step" when creating new RRDs unless rrdtool plugin forces StepSize. Also used to detect values that have timed out.
    const PART_TYPE_INTERVAL_HD      = 0x0009;   // Numeric : The interval in which subsequent data values are collected. The interval is given in 2–30 seconds. New in Version 5.0.
    const PART_TYPE_MESSAGE          = 0x0100;   // String
    const PART_TYPE_SEVERITY         = 0x0101;   // Numeric
    const PART_TYPE_SIGNATURE        = 0x0200;   // Signature (HMAC-SHA-256) : other (todo)
    const PART_TYPE_ENCRYPTION       = 0x0210;   // Encryption (AES-256/OFB/SHA-1) : other (todo)

    const DATA_TYPE_COUNTER          = 0;
    const DATA_TYPE_GAUGE            = 1;
    const DATA_TYPE_DERIVE           = 2;
    const DATA_TYPE_ABSOLUTE         = 3;

    /**
     * @param string          $host
     * @param WriterInterface $writer
     */
    public function __construct($host, WriterInterface $writer)
    {
        $this->host = $host;
        $this->writer = $writer;
    }

    /**
     * @param $timestamp
     *
     * @return binary the binary representation of a timestamp
     */
    protected function buildTimestamp($timestamp)
    {
        return pack('nnNN', self::PART_TYPE_TIME, 12, 0, $timestamp);
    }

    /**
     * @param $type
     * @param $string
     *
     * @return binary
     */
    protected function buildString($type, $string)
    {
        return pack('nn', $type, strlen($string) + 5) . $string . pack('x');
    }

    /**
     * The length field of those parts must therefore always be set to 12
     *
     * @param MetricInterface $metric
     * @param integer        $timestamp
     *
     * @return binary
     */
    protected function getBasePacket(MetricInterface $metric, $timestamp)
    {
        $packet = $this->buildTimestamp($timestamp);
        $packet .= $this->buildString(self::PART_TYPE_HOST, $this->host);
        $packet .= $this->buildString(self::PART_TYPE_PLUGIN, $metric->getName());
//        $packet .= $this->buildString(self::PART_TYPE_PLUGIN_INSTANCE, 'none');

        return $packet;
    }

    /**
     * @param GaugeInterface $metric
     * @param integer        $timestamp
     *
     * @return binary
     */
    protected function buildGauge(GaugeInterface $metric, $timestamp)
    {
        $packet = $this->getBasePacket($metric, $timestamp);

        $packet .= $this->buildString(self::PART_TYPE_TYPE, 'gauge');
//        $packet .= $this->buildString(self::PART_TYPE_TYPE_INSTANCE, 'none');

        $data = pack("nCd", 1, self::DATA_TYPE_GAUGE, $metric->getValue());

        $packet .= pack('nn', self::PART_TYPE_VALUES, strlen($data) + 4) . $data;

        return $packet;
    }

    /**
     * @param TimerInterface $metric
     * @param integer        $timestamp
     *
     * @return binary
     */
    protected function buildTimer(TimerInterface $metric, $timestamp)
    {
        $packet = $this->getBasePacket($metric, $timestamp);

        $packet .= $this->buildString(self::PART_TYPE_TYPE, 'gauge');
//        $packet .= $this->buildString(self::PART_TYPE_TYPE_INSTANCE, 'none');

        $data = pack("nCd", 1, self::DATA_TYPE_GAUGE, $metric->getValue());

        $packet .= pack('nn', self::PART_TYPE_VALUES, strlen($data) + 4) . $data;

        return $packet;
    }

    /**
     * @param array $metrics
     *
     * @return void
     */
    public function send(array $metrics)
    {
        $bin = '';
        foreach ($metrics as $data) {
            list($metric, $timestamp) = $data;

            if ($metric instanceof TimerInterface) {
                $bin .= $this->buildTimer($metric, $timestamp);
            } elseif ($metric instanceof GaugeInterface) {
                $bin .= $this->buildGauge($metric, $timestamp);
            } else {
                throw new \RuntimeException('Metric not implement');
            }
        }

        if (!empty($bin)) {
            $this->writer->write($bin);
        }
    }
}