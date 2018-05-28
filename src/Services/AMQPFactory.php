<?php

namespace Services;

use AMQPConnection;
use RuntimeException;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AMQPFactory implements FactoryInterface
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
        if (! extension_loaded('AMQP')) {
            throw new RuntimeException(
                'The AMQP extension has not been installed or enabled.'
            );
        }
        $amqp = $container->get('loader')
            ->loadEnvConfigFile('amqp.yaml', true)
            ->amqp;

        $params['port']  = empty($params['port']) ? "5672" : $params['port'];
        $params['vhost'] = empty($params['vhost']) ? "/" : $params['vhost'];

        $connection = new AMQPConnection;
        $connection->setHost($params['host']);
        $connection->setPort($params['port']);
        $connection->setLogin($params['username']);
        $connection->setPassword($params['password']);
        $connection->setVHost($params['vhost']);
        $connection->connect();

        return $connection;
    }
}