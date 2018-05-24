<?php

namespace App\Middleware;

use Psr\Http\{
    Message\ResponseInterface,
    Message\ServerRequestInterface as Request,
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface as RequestHandler
};
use Obullo\Mvc\Container\{
    ContainerAwareTrait,
    ContainerAwareInterface
};
class Translation implements MiddlewareInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        $container = $this->getContainer();

        $router     = $container->get('router');
        $translator = $container->get('translator');

        if ($router->hasMatch()) {
            $route = $router->getMatchedRoute();
            $translator->setLocale($route->getArgument('locale'));
            $route->removeArgument('locale');
        }
        return $handler->handle($request);
    }
}