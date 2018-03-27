<?php

namespace Services;

use League\Container\ServiceProvider\AbstractServiceProvider;

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

class Logger extends AbstractServiceProvider
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
        'logger',
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

        $monolog = $container->get('loader')
            ->load('/config/%env%/monolog.yaml', true)
            ->monolog;

        $logger = $container->share('logger', 'Monolog\Logger')
            ->withArgument($monolog->default_channel);

        if (false == $monolog->enabled) {
            $logger->withMethodCall(
                'pushHandler',
                [new NullHandler]
            );
            return;
        }
        if ($monolog->enabled && $monolog->debug) {
            $logger->withMethodCall(
                'pushHandler',
                [new StreamHandler(ROOT .'/var/log/debug.log', Log::DEBUG, true, 0666)]
            );
        }
    }
}