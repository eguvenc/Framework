<?php

namespace App\Middleware;

use Psr\Http\{
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface as RequestHandler,
    Message\ResponseInterface,
    Message\ServerRequestInterface as Request
};
use Obullo\Mvc\Application as Mvc;

class Router implements MiddlewareInterface
{
    protected $app;

    /**
     * Constructor
     * 
     * @param app $app application
     */
    public function __construct(Mvc $app)
    {
        $this->app = $app;
    }

    /**
     * Process request
     *
     * @param ServerRequestInterface  $request  request
     * @param RequestHandlerInterface $handler
     *
     * @return object ResponseInterface
     */
    public function process(Request $request, RequestHandler $handler) : ResponseInterface
    {
        $router = $this->app->getContainer()->get('router');

        if ($route = $router->matchRequest()) {
            $methods = $route->getMethods();
            if (! in_array($request->getMethod(), $methods)) {
                return $handler->process(new NotAllowed($methods));
            }
            $request = $request->withAttribute('locale', $route->getArgument('locale'));
            $route->removeArgument('locale');

            $response = $this->app->handle($request, $route);
            if ($response instanceof ResponseInterface) {
                return $response;
            }
        }
        return $handler->process(new Error('404'));
    }
}