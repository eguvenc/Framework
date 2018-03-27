<?php

namespace Obullo\Mvc;

use Psr\{
    Container\ContainerInterface as Container,
    Http\Message\ServerRequestInterface as Request,
    Http\Message\ResponseInterface as Response
};
use Obullo\Mvc\Dependency\Resolver;
use Obullo\Mvc\{
    Container\ContainerAwareTrait,
    Container\ContainerAwareInterface
};
use ReflectionClass;
use RuntimeException;

use Obullo\Router\Router;
use Obullo\Mvc\Config\LoaderInterface as Loader;
use Obullo\Http\Stack\StackInterface as Stack;

/**
 * Mvc application
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
abstract class Application implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $env;
    protected $response;
    
    abstract protected function configureConfig(Container $container);
    abstract protected function configureContainer(Container $container);
    abstract protected function configureRouter(Container $container, Loader $loader, Request $request);
    
    /**
     * Constructor
     * 
     * @param string $env env
     */
    public function __construct(string $env)
    {
        $this->env = $env;
    }

    /**
     * Initialize to configurations
     *
     * @param Request $request request
     * 
     * @return void
     */
    public function start(Request $request)
    {
        $container = $this->getContainer();
        $container->share('request', $request);
        $this->configureConfig($container);
        $this->configureContainer($container);
        $this->configureRouter($container, $container->get('loader'), $request);

        $container->get('router')->matchRequest($request);
    }
    
    /**
     * Build route middlewares
     * 
     * @return handler
     */
    public function build() : array
    {
        $container = $this->getContainer();
        $middlewares = array();
        foreach ($container->get('router')->getStack() as $class) {
            $reflection = new ReflectionClass($class);
            $resolver = new Resolver($reflection);
            $resolver->setContainer($container);
            $args = array();
            if ($reflection->hasMethod('__construct')) {
                $args = $resolver->resolve('__construct');
            }
            $middlewares[] = $reflection->newInstanceArgs($args);
        }
        return $middlewares;
    }

    /**
     * Returns to env
     * 
     * @return string
     */
    public function getEnv() : string
    {
        return $this->env;
    }

    /**
     * Handle application process
     * 
     * @param  Request $request Psr Request
     * @param  Route   $router  Router
     * 
     * @return null|Response
     */
    public function handle(Request $request, Router $router)
    {
        $container = $this->getContainer();
        $container->share('request', $request);

        $route = $router->getMatchedRoute();
        $handler = $route->getHandler();
        $response = null;
        if (is_callable($handler)) {
            $exp = explode('::', $handler);
            $class  = $exp[0];
            $method = $exp[1];
            $arguments = $route->getArguments();
            $resolver = new Resolver(new ReflectionClass($class));
            $resolver->setContainer($this->getContainer());
            $resolver->setArguments($arguments);
            $injectedParameters = $resolver->resolve($method);
            $controller = new $class;
            $controller->setContainer($container);
            $response = call_user_func_array(
                array(
                    $controller,
                    $method
                ),
                $injectedParameters
            );
        }
        return $response;
    }

    /**
     * Emit response
     * 
     * @return void
     */
    public function emit(Response $response)
    {
        if (headers_sent()) {
            throw new RuntimeException('Unable to emit response; headers already sent');
        }
        if (ob_get_level() > 0 && ob_get_length() > 0) {
            throw new RuntimeException('Output has been emitted previously; cannot emit response');
        }
        $this->response = $response;
        $this->emitHeaders();
        $this->emitBody();
    }

    /**
     * Emit headers
     *
     * @return void
     */
    protected function emitHeaders()
    {
        $statusCode = $this->response->getStatusCode();
        foreach ($this->response->getHeaders() as $header => $values) {
            $name = $header;
            foreach ($values as $value) {
                header(sprintf(
                    '%s: %s',
                    $name,
                    $value
                ), true, $statusCode);
            }
        }
        $container = $this->getContainer();
        if ($container->has('cookie')) {
            foreach ($container->get('cookie')->toArray() as $name => $cookie) {
                setcookie(
                    $name,
                    $cookie['value'],
                    $cookie['expire'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httpOnly']
                );   
            }
        }
    }

    /**
     * Emit body
     * 
     * @return void
     */
    protected function emitBody()
    {
        echo $this->response->getBody();
    }

    /**
     * Terminate application
     * 
     * @return void
     */
    public function terminate() {}
}