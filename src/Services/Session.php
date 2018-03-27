<?php

namespace Services;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

use League\Container\ServiceProvider\AbstractServiceProvider;

class Session extends AbstractServiceProvider
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
        'session'
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

        $storage = new NativeSessionStorage(array(), new NativeFileSessionHandler());
        $session = new \Symfony\Component\HttpFoundation\Session\Session($storage);
        $session->setName($app->session->name);
        $session->start();

        $container->share('session', $session);
    }
}