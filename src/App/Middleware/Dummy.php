<?php

namespace App\Middleware;

use Psr\Http\{
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface as RequestHandler,
    Message\ResponseInterface,
    Message\ServerRequestInterface as Request
};

class Dummy implements MiddlewareInterface
{
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
        return $handler->handle($request);
    }

}