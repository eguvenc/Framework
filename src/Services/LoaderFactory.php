<?php

namespace Services;

use Obullo\Mvc\Config\Cache\FileHandler;
use Obullo\Mvc\Config\Loader\YamlLoader;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LoaderFactory implements FactoryInterface
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
        $cacheHandler = new FileHandler('/var/cache/config/');

        // To change default cache handler uncomment doc blocks and set your own.

        // use Obullo\Mvc\Config\Cache\RedisHandler;
        // $cacheHandler = new RedisHandler($container->get('redis'));

        $loader = new YamlLoader($cacheHandler);

        $env = getenv('APP_ENV');

        // Put all config files here you want to load at bootstrap.

        \Zend\Config\Factory::fromFiles(
            [
                ROOT.'/config/'.$env.'/framework.yaml',
            ]
        );

        return $loader;
    }
}