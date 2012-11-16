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

use Ekino\Metric\Type\Xhprof;
use Ekino\Metric\Reporter\XHGui\ParameterResolverInterface;

/**
 * Reference :
 *    https://github.com/preinheimer/xhprof/blob/master/xhprof_lib/utils/Db/Pdo.php
 *    https://github.com/jonaswouters/XhprofBundle/blob/master/DataCollector/XhprofCollector.php
 *
 */
class XHGuiReporter implements ReporterInterface
{
    protected $dsn;

    protected $statement;

    protected $login;

    protected $password;

    protected $options;

    protected $serverId;

    protected $parameterResolver;

    /**
     * @param string                     $dsn
     * @param string                     $login
     * @param string                     $password
     * @param array                      $options
     * @param string                     $serverId
     * @param ParameterResolverInterface $parameterResolver
     */
    public function __construct($dsn, $login, $password, $options, $serverId, ParameterResolverInterface $parameterResolver)
    {
        $this->dsn = $dsn;
        $this->login = $login;
        $this->password = $password;
        $this->options = $options;
        $this->serverId = $serverId;
        $this->parameterResolver = $parameterResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function send(array $metrics)
    {
        foreach ($metrics as $data) {
            list($metric, $timestamp) = $data;

            if (!$metric instanceof Xhprof) {
                continue;
            }

            $this->report($metric, $timestamp);
        }
    }

    /**
     * @return \Pdo
     */
    protected function getStatement()
    {
        if (!$this->statement) {

            $pdo = new \Pdo($this->dsn, $this->login, $this->password, $this->options);

            $sql = 'INSERT INTO details (`id`, `url`, `c_url`, `timestamp`, `server name`, `perfdata`, `type`, `cookie`, `post`, `get`, `pmu`, `wt`, `cpu`, `server_id`, `aggregateCalls_include`)
                                VALUES (:run_id, :url, :canonical_url, :timestamp, :server_name, :perfdata, 0, :cookie, :post, :get, :pmu, :wt, :cpu, :server_id, \'\');';

            $this->statement = $pdo->prepare($sql);
        }

        return $this->statement;
    }

    /**
     * @param Xhprof  $metric
     * @param integer $timestamp
     *
     * @return string
     */
    protected function report(Xhprof $metric, $timestamp)
    {
        $stat = $metric->getSample()->getStat();

        $this->getStatement()->execute(array(
            ':run_id'        => $metric->getName(),
            ':url'           => $this->parameterResolver->getUrl(),
            ':canonical_url' => $this->parameterResolver->getCanonicalUrl(),
            ':timestamp'     => $timestamp,
            ':server_name'   => $this->parameterResolver->getServerName(),
            ':perfdata'      => gzcompress(json_encode($metric->getSample()->getData()), 2),
            ':cookie'        => json_encode($this->parameterResolver->getCookie()),
            ':post'          => json_encode($this->parameterResolver->getPost()),
            ':get'           => json_encode($this->parameterResolver->getGet()),
            ':pmu'           => $stat->getPeakMemoryUsage(),
            ':wt'            => $stat->getTime(),
            ':cpu'           => $stat->getCpu(),
            ':server_id'     => $this->serverId,
        ));

        return $metric->getName();
    }
}