<?php

namespace App\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;

use Psr\Http\Message\ResponseInterface;
use Obullo\Mvc\Container\{
    ContainerAwareInterface,
    ContainerAwareTrait
};
use Obullo\Mvc\Middleware\Error;

class ErrorListener implements ListenerAggregateInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = null)
    {
        $this->listeners[] = $events->attach('error.404', [$this, 'on404Error']);
        $this->listeners[] = $events->attach('error.handler', [$this, 'onErrorHandler']);
        $this->listeners[] = $events->attach('error.response', [$this, 'onErrorResponse']);
    }

    public function on404Error(EventInterface $e) : Error
    {
        // $e->getParam('request');
        // $e->getParam('repsonse');

        return new Error('404');
    }

    public function onErrorHandler(EventInterface $e)
    {
        $error = $e->getParams();

        if (is_object($error)) {
            switch ($error) {
                case ($error instanceof Throwable):
                case ($error instanceof RuntimeException):
                    // error log
                    break;
            }
        }
    }

    public function onErrorResponse(EventInterface $e)
    {
        $response = $e->getParams();
        return $response;
    }
}
