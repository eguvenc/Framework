<?php

namespace Services;

use RuntimeException;
use League\Container\ServiceProvider\AbstractServiceProvider;

class Redis extends AbstractServiceProvider
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
        'redis'
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

        if (! extension_loaded('redis')) {
            throw new RuntimeException(
                'The redis extension has not been installed or enabled.'
            );
        }
        $redis = $container->get('loader')
            ->load('/config/%env%/redis.yaml', true)
            ->redis;

        $client = new \Redis;
        $client->connect($redis->host, $redis->port);
        $client->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);

        /* 
        // OR Predis 
        $client = new Predis\Client([
            'scheme' => $redis->scheme,
            'host'   => $redis->host,
            'port'   => $redis->port,
        ]);
        */
        $container->share('redis', $client);
    }
}