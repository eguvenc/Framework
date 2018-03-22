<?php

namespace App\Middleware;

use Psr\Http\{
    Message\ResponseInterface,
    Message\ServerRequestInterface as Request,
    Server\MiddlewareInterface,
    Server\RequestHandlerInterface as RequestHandler
};
use Zend\Diactoros\Response\HtmlResponse;

class NotAllowed implements MiddlewareInterface
{
    protected $allowedMethods;

    /**
     * Constructor
     * 
     * @param array $allowedMethods methods
     */
    public function __construct($allowedMethods)
    {
        $this->allowedMethods = (array)$allowedMethods;
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
        $html = sprintf(
            'Only Http %s Methods Allowed',
            implode(', ', $this->allowedMethods)
        );
        // return new JsonResponse($json, 405, [], JSON_PRETTY_PRINT);

        return new HtmlResponse($html, 405, ['Allow' => implode(', ', $this->getAllowedMethods())]);
    }

    /**
     * Returns to allowed http methods
     * 
     * @return array
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }
}