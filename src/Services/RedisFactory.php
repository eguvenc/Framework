<?php

namespace Services;

use Redis;
use RuntimeException;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class RedisFactory implements FactoryInterface
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
        if (! extension_loaded('redis')) {
            throw new RuntimeException(
                'The redis extension has not been installed or enabled.'
            );
        }
        $redis = $container->get('loader')
            ->loadEnvConfigFile('redis.yaml', true)
            ->redis;

        $client = new Redis;
        $client->connect($redis->host, $redis->port);
        $client->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        /* 
        // OR Predis 
        $client = new Predis\Client([
            'scheme' => $redis->scheme,
            'host'   => $redis->host,
            'port'   => $redis->port,
        ]);
        */
        return $client;
    }
}