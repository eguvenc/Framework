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

use Obullo\Router\RouteInterface as Route;
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
        $this->configureConfig($container);
        $this->configureContainer($container);
        $this->configureRouter($container, $container->get('loader'), $request);
    }
    
    /**
     * Build middlewares
     * 
     * @return handler
     */
    public function build() : Stack
    {
        $container = $this->getContainer();

        foreach ($router->getStack() as $value) {
            $resolver = new Resolver(new ReflectionClass($class));
            $resolver->setContainer($container);
            $resolver->resolve('__construct');

            $handler = $stack->withMiddleware($value);
        }
        return $handler;
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
     * @param  Route   $route   RouteInterface
     * 
     * @return null|Response
     */
    public function handle(Request $request, Route $route)
    {
        $container = $this->getContainer();
        $container->share('request', $request);

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
            $name  = $header;
            $first = $name === 'Set-Cookie' ? false : true;
            foreach ($values as $value) {
                header(sprintf(
                    '%s: %s',
                    $name,
                    $value
                ), $first, $statusCode);
                $first = false;
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