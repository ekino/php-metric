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

class CliParameterResolver implements ParameterResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return 'cli';
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalUrl()
    {
        return 'cli';
    }

    /**
     * {@inheritdoc}
     */
    public function getCookie()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getPost()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getGet()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getServerName()
    {
        return gethostname();
    }
}
