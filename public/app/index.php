<?php

use League\Container\{
    Container,
    ReflectionContainer
};
use Dotenv\Dotenv;
use Zend\Diactoros\ServerRequestFactory;
use App\Config;
use App\Middleware\{
    Error,
    Router
};
use Obullo\Stack\Builder;

require '../../vendor/autoload.php';

$time_start = microtime(true);

define('ROOT', dirname(dirname(__DIR__)));

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
$app = new Config($env);
$app->setContainer($container);
$app->start($request);

$stack = new Builder;
$handler = $stack->withMiddleware(new Error);
foreach ($app->build($stack) as $middleware) {
    $handler = $stack->withMiddleware($middleware);
}
$response = $stack->withMiddleware(new Router($app))
    ->process($request);

$app->emit($response);
$app->terminate();

$time_end = microtime(true);
// echo number_format($time_end - $time_start, 4);