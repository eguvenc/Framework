<?php

use League\Container\{
    Container,
    ReflectionContainer
};
use Dotenv\Dotenv;
use Zend\Diactoros\ServerRequestFactory;
use Obullo\Mvc\Module;
use Obullo\Stack\Builder as Stack;

require '../vendor/autoload.php';

define('ROOT', dirname(__DIR__));

if (false == isset($_SERVER['APP_ENV'])) {
    (new Dotenv(ROOT))->load();
}
$env = getenv('APP_ENV');

if ('prod' !== $env) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
$request = ServerRequestFactory::fromGlobals();
$container = new Container;
$container->delegate(
    new ReflectionContainer
);
require 'bootstrap.php';

$module = new Module($router);
$module->setContainer($container);
$module->build();
$config = "{$module->getName()}\Config";

$app = new $config($env);
$app->setModule($module);
$app->setStack(new Stack);
$response = $app->process($request);
$app->emit($response);
$app->terminate();