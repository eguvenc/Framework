<?php

namespace App\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\ListenerAggregateInterface;

use Obullo\Mvc\Container\{
    ContainerAwareInterface,
    ContainerAwareTrait
};
class HttpMethodListener implements ListenerAggregateInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = null)
    {
        $this->listeners[] = $events->attach('http.method.notAllowed', [$this, 'onNotAllowedMethod']);
        $this->listeners[] = $events->attach('http.method.allowed', [$this, 'onAllowedMethod']);
        $this->listeners[] = $events->attach('http.method.notAllowed.message', [$this, 'onNotAllowedMessage']);
    }

    public function onNotAllowedMethod(EventInterface $e)
    {
        // $methods = $e->getParams();
    }

    public function onAllowedMethod(EventInterface $e)
    {
        // $methods = $e->getParams();
    }

    public function onNotAllowedMessage(EventInterface $e) : string
    {
        $methods = $e->getParams();
        $message = sprintf(
            'Only Http %s Methods Allowed',
            implode(', ', $methods)
        );
        return $message;
    }
}