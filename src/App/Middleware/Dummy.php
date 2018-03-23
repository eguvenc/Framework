<?php

namespace App\Middleware;

use Psr\Http\{
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface as RequestHandler,
    Message\ResponseInterface,
    Message\ServerRequestInterface as Request
};

class Dummy
{
    /**
     * Constructor
     * 
     * @param app $app application
     */
    public function __construct(Request $request)
    {

    }
}