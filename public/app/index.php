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

// $time_start = microtime(true);

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

use Obullo\Mvc\Dependency\Resolver;

$container->share('request', $request);
$resolver = new Resolver(new ReflectionClass('App\Middleware\Dummy'));
$resolver->setContainer($container);
$params = $resolver->resolve('__construct');

var_dump($params);
die;

$app = new Config($env);
$app->setContainer($container);
$app->start($request);

$stack = new Builder;
$handler = $stack->withMiddleware(new Error);
$handler = $app->build($stack);
$handler = $stack->withMiddleware(new Router($app));

$response = $handler->process($request);

$app->emit($response);
$app->terminate();

// $time_end = microtime(true);
// echo number_format($time_start - $time_end, 4);