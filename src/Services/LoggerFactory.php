<?php

namespace Services;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LoggerFactory implements FactoryInterface
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
        $monolog = $container->get('loader')
            ->loadEnvConfigFile('monolog.yaml', true)
            ->monolog;

        $logger = new Logger($monolog->default_channel);

        if (false == $monolog->enabled) {
            $logger->pushHandler(new NullHandler);
            return $logger;
        }
        if ($monolog->debug) {
            $logger->pushHandler(new StreamHandler(ROOT .'/var/log/debug.log', Logger::DEBUG, true, 0666));
        }
        return $logger;
    }
}