<?php

namespace Services;

use AMQPConnection;
use RuntimeException;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AMQP extends AbstractServiceProvider
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
        'amqp'
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

        if (! extension_loaded('AMQP')) {
            throw new RuntimeException(
                'The AMQP extension has not been installed or enabled.'
            );
        }
        $amqp = $container->get('loader')
            ->load('amqp.yaml', true)
            ->redis;

        $params['port']  = empty($params['port']) ? "5672" : $params['port'];
        $params['vhost'] = empty($params['vhost']) ? "/" : $params['vhost'];

        $connection = new AMQPConnection;
        $connection->setHost($params['host']);
        $connection->setPort($params['port']);
        $connection->setLogin($params['username']);
        $connection->setPassword($params['password']);
        $connection->setVHost($params['vhost']);
        $connection->connect();

        $container->share('amqp', $connection);
    }
}