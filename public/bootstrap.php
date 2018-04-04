<?php

// -------------------------------------------------------------------
// Loader Cache
// -------------------------------------------------------------------
// This section contains configuration loader & configuration cache
// settings of your application.
// 

use Obullo\Mvc\Config\LoaderInterface;
use Obullo\Mvc\Config\Loader\YamlLoader;

$loader = new YamlLoader('/tests/var/cache/config/');
$container->share('loader', $loader);

// This part contains configuration cache settings and the
// default cache handler is file.
// The application always needs file handler to cache system
// configuration files.
//
// To override file handler uncomment doc blocks and set your cache handler.

// use Obullo\Mvc\Config\Cache\RedisHandler;
// use Obullo\Mvc\Config\Reader\YamlReader;

// $container->addServiceProvider('Services\Redis');
// $cacheHandler = new RedisHandler($container->get('redis'));
// Zend\Config\Factory::registerReader('yaml',new YamlReader($cacheHandler));

// -------------------------------------------------------------------
// Router
// -------------------------------------------------------------------
// This section contains route configuration & route match operation
// of your application.
//

use Obullo\Router\{
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
$context = new RequestContext;
$context->fromRequest($request);

$collection = new RouteCollection(array(
    'types' => [
        new IntType('<int:id>'),
        new IntType('<int:page>'),
        new StrType('<str:name>'),
        new TranslationType('<locale:locale>'),
    ]
));
$collection->setContext($context);
$builder = new Builder($collection);

$routes = $loader->load('/config/routes.yaml');
$collection = $builder->build($routes);

$router = new Router($collection);
$router->matchRequest($request);