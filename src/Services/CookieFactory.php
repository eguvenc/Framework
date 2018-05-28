<?php

namespace Services;

use Obullo\Mvc\Http\Cookie;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CookieFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $cookie = $container->get('loader')
            ->loadEnvConfigFile('framework.yaml', true)
            ->framework
            ->cookie;

        $params = [
            'domain' => $cookie->domain,
            'path'   => $cookie->path,
            'secure' => $cookie->secure,
            'httpOnly' => $cookie->httpOnly,
            'expire' => $cookie->expire,
        ];
        $requestCookies = $container->get('request')
            ->getCookieParams();
        $cookie = new Cookie($requestCookies);
        $cookie->setDefaults($params);
        
        return $cookie;
    }
}