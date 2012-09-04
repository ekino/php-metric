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

interface XHGuiParameterResolverInterface
{
    /**
     * @return string
     */
    function getUrl();

    /**
     * @return string
     */
    function getCanonicalUrl();

    /**
     * @return array
     */
    function getCookie();

    /**
     * @return array
     */
    function getPost();

    /**
     * @return array
     */
    function getGet();

    /**
     * @return string
     */
    function getServerName();
}
