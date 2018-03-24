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
class Config extends Application
{
	protected function configureConfig(Container $container)
	{
        $cacheHandler = new FileHandler;
        \Zend\Config\Factory::registerReader('yaml',new YamlReader($cacheHandler));

        $config = \Zend\Config\Factory::fromFiles(
            [
                ROOT.'/config/'.$this->getEnv().'/database.yaml',
                ROOT.'/config/'.$this->getEnv().'/framework.yaml',
                ROOT.'/config/'.$this->getEnv().'/monolog.yaml',
            ]
        );
        $container->share('loader', new Loader);
	}

    protected function configureContainer(Container $container)
    {
        $container->addServiceProvider('Services\Template');
        $container->addServiceProvider('Services\Logger');
        // $container->addServiceProvider('Services\Session');
        // $container->addServiceProvider('Services\Database');
        // $container->addServiceProvider('Services\Predis');
        // $container->addServiceProvider('Services\Redis');
        // $container->addServiceProvider('Services\Memcached');
        // $container->addServiceProvider('Services\Mongo');
    }

    protected function configureRouter(Container $container, LoaderInterface $loader, Request $request)
    {
        $context = new RequestContext;
        $context->fromRequest($request);

        $config = array(
            'types' => [
                new IntType('<int:id>'),
                new IntType('<int:page>'),
                new StrType('<str:name>'),
                new TranslationType('<locale:locale>'),
            ]
        );
        $collection = new RouteCollection($config);
        $collection->setContext($context);
        $builder = new Builder($collection);
        $collection = $builder->build($loader->load('/config/routes.yaml'));

        $container->share('router', new Router($collection));
    }
}