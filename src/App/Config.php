<?php

namespace App;

use Psr\{
    Container\ContainerInterface as Container,
    Http\Message\ServerRequestInterface as Request
};
use Obullo\Mvc\Application;
use Obullo\Mvc\Config\{
    LoaderInterface,
    Loader,
    Reader\YamlReader,
    Cache\FileHandler
};
use Obullo\Router\{
    Route,
    RequestContext,
    RouteCollection,
    Router,
    Builder
};
use Obullo\Router\Types\{
    StrType,
    IntType,
    TranslationType
};
use Obullo\Http\Stack\StackInterface as Stack;

class Config extends Application
{
    protected function configureConfig(Container $container)
    {
        $config = \Zend\Config\Factory::fromFiles(
            [
                ROOT.'/config/'.$this->getEnv().'/app.yaml',
            ]
        );
    }

    protected function configureContainer(Container $container)
    {
        $container->addServiceProvider('Services\Template');
        $container->addServiceProvider('Services\Logger');
        $container->addServiceProvider('Services\Cookie');
        // $container->addServiceProvider('Services\Session');
        // $container->addServiceProvider('Services\Database');
        // $container->addServiceProvider('Services\Predis');
        // $container->addServiceProvider('Services\Redis');
        // $container->addServiceProvider('Services\Memcached');
        // $container->addServiceProvider('Services\Mongo');
    }

    protected function configureMiddleware(Stack $stack) : Stack
    {
        $module = $this->getModule()->getName();

        $Error  = "{$module}\Middleware\Error";
        $Router = "{$module}\Middleware\Router";

        $stack = $stack->withMiddleware(new $Error);
        foreach ($this->build($stack) as $middleware) {
            $stack = $stack->withMiddleware($middleware);
        }
        return $stack->withMiddleware(new $Router($this));
    }
}