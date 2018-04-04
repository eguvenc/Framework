<?php

namespace Services;

use League\Container\ServiceProvider\AbstractServiceProvider;

class Cookie extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'cookie'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();
        
        $app = $container->get('loader')
            ->load('/config/%env%/app.yaml', true)
            ->app;

        $params = [
            'domain' => $app->domain,
            'path'   => $app->path,
            'secure' => $app->secure,
            'httpOnly' => $app->httpOnly,
            'expire' => $app->expire,
        ];
        $requestCookies = $container->get('request')
            ->getCookieParams();
        $cookie = new \Obullo\Mvc\Http\Cookie($requestCookies);
        $cookie->setDefaults($params);

        $container->share('cookie', $cookie);
    }
}