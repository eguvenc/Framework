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
// $container->setFactory('database', 'Services\DatabaseFactory');
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
// Application
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