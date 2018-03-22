<?php

namespace Services;

use League\Container\ServiceProvider\AbstractServiceProvider;

class Memcached extends AbstractServiceProvider
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
        'memcached'
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

        if (! extension_loaded('memcached')) {
            throw new RuntimeException(
                'The memcached extension has not been installed or enabled.'
            );
        }
        $client = new \Memcached;
        $client->addServer('127.0.0.1', 11211);
        $client->setOption(\Memcached::OPT_SERIALIZER, \Memcached::SERIALIZER_PHP);

        $container->share('memcached', $client);
    }
}