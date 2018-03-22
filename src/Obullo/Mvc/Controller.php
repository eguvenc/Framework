<?php

namespace Obullo\Mvc;

use Obullo\Mvc\Container\{
    ContainerProxyTrait,
    ContainerAwareInterface
};

/**
 * Controller.
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Controller implements ContainerAwareInterface
{
    use ContainerProxyTrait;

    /**
     * Route generator helper
     * 
     * @param  string $url    string
     * @param  array  $params optional parameters
     * @return string
     */
    public function url(string $url, $params = array())
    {
        $func = $this->template->getEngine()
            ->getFunction('url')
            ->getCallback();

        return call_user_func_array($func, array($url, $params));
    }
}