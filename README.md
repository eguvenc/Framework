
# Obullo / Skeleton

[![Build Status](https://travis-ci.org/obullo/Skeleton.svg?branch=master)](https://travis-ci.org/obullo/Skeleton)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

> It provides the skeleton needed to build your micro mvc project.

## Creating a new project

``` bash
$ composer create-project obullo/skeleton
```

## Requirements

The following versions of PHP are supported by this version.

* 7.0
* 7.1
* 7.2

## Testing web services

``` bash
$ vendor/bin/phpunit
```

## Configure packages

Use your composer.json.

```
{
    "autoload": {
        "psr-4": {
            "": "src/"
        }
    },
    "require": {
        "php": "^7.0",
        "phpunit/phpunit": "^4.8",
        "obullo/mvc": "^1.0",
        "obullo/stack": "^1.0",
        "obullo/router": "^1.0",
        "league/plates": "^3.3",
        "doctrine/dbal": "^2.6",
        "monolog/monolog": "^1.23",
        "symfony/console": "^4.0",
        "vlucas/phpdotenv": "^2.4",
        "zendframework/zend-config": "^3.1",
        "zendframework/zend-diactoros": "^1.7",
        "zendframework/zend-servicemanager": "^3.3",
        "zendframework/zend-session": "^2.8",
        "zendframework/zend-i18n-resources": "^2.5",
        "zendframework/zend-i18n": "^2.7",
        "zendframework/zend-escaper": "^2.6"
    }
}
```

## Configure everything from index.php

```php
<?php

require '../../vendor/autoload.php';

define('ROOT', dirname(dirname(__DIR__)));
define('APP', 'App');

use Obullo\Mvc\Application;
use Zend\ServiceManager\ServiceManager;
use Dotenv\Dotenv;

// The check is to ensure we don't use .env in production

if (false == isset($_SERVER['APP_ENV'])) {
    (new Dotenv(ROOT))->load();
}
$env = $_SERVER['APP_ENV'] ?? 'dev';

if ('prod' !== $env) {
    ini_set('display_errors', 1);  
    error_reporting(E_ALL);
}
// -------------------------------------------------------------------
// Service Manager
// -------------------------------------------------------------------
//
$container = new ServiceManager;
$container->setFactory('loader', 'Services\LoaderFactory');
$container->setFactory('translator', 'Services\TranslatorFactory');
$container->setFactory('events', 'Services\EventManagerFactory');
$container->setFactory('request', 'Services\RequestFactory');
$container->setFactory('session', 'Services\SessionFactory');
// $container->setFactory('redis', 'Services\RedisFactory');
$container->setFactory('database', 'Services\DatabaseFactory');
$container->setFactory('view', 'Services\ViewPlatesFactory');
$container->setFactory('logger', 'Services\LoggerFactory');
$container->setFactory('cookie', 'Services\CookieFactory');
$container->setFactory('flash', 'Services\FlashMessengerFactory');
$container->setFactory('error', 'Services\ErrorHandlerFactory');
$container->setFactory('escaper', 'Services\EscaperFactory');

// -------------------------------------------------------------------
// Handle Exceptions
// -------------------------------------------------------------------
//
set_exception_handler(array($container->get('error'), 'handle'));

// -------------------------------------------------------------------
// Events
// -------------------------------------------------------------------
//
$listeners = [
    'App\Event\SessionListener',
    'App\Event\ErrorListener',
    'App\Event\RouteListener',
    // 'App\Event\HttpMethodListener',
    'App\Event\SendResponseListener',
];

$application = new Application($container, $listeners);
$application->start();

// -------------------------------------------------------------------
// Stack Queue
// -------------------------------------------------------------------
//
$queue = [
    new Obullo\Mvc\Middleware\Error,
    new Obullo\Mvc\Middleware\HttpMethod,
];
$queue = $application->mergeQueue($queue);

// -------------------------------------------------------------------
// Process
// -------------------------------------------------------------------
//
$response = $application->process($queue, $container->get('request'));
$application->sendResponse($response);
$application->terminate();
```

## Documentation

Documents are available at <a href="http://mvc.obullo.com/">http://mvc.obullo.com/</a>