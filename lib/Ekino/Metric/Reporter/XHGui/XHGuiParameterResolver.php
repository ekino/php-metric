<?php

/*
 * This file is part of the Ekino PHP metric project.
 *
 * (c) Ekino - Thomas Rabaix <thomas.rabaix@ekino.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\Metric\Reporter\XHGui;

class XHGuiParameterResolver implements XHGuiParameterResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'n/a';
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalUrl()
    {
        return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'n/a';
    }

    /**
     * {@inheritdoc}
     */
    public function getCookie()
    {
        return $_COOKIE;
    }

    /**
     * {@inheritdoc}
     */
    public function getPost()
    {
        return $_POST;
    }

    /**
     * {@inheritdoc}
     */
    public function getGet()
    {
        return $_GET;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'n/a';
    }
}
