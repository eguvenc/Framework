<?php

namespace Services;

use Memcached;
use RuntimeException;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MemcachedFactory implements FactoryInterface
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
        if (! extension_loaded('memcached')) {
            throw new RuntimeException(
                'The memcached extension has not been installed or enabled.'
            );
        }
        $client = new Memcached;
        $client->addServer('127.0.0.1', 11211);
        $client->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP);

        return $client;
    }
}