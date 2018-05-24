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
use Psr\Http\Message\ResponseInterface;

class SendResponseListener implements ListenerAggregateInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = null)
    {
        $this->listeners[] = $events->attach('response.before.headers', [$this, 'onBeforeHeaders']);
        $this->listeners[] = $events->attach('response.before.emit', [$this, 'onBeforeEmit']);
        $this->listeners[] = $events->attach('response.after.emit', [$this, 'onAfterEmit']);
    }

    public function onBeforeHeaders(EventInterface $e)
    {
        // $response = $e->getParam('response');
        // $app = $e->getTarget();
        // $router = $app->getRouter();
        // $dispatcher = $app->getDispatcher();
    }

    public function onBeforeEmit(EventInterface $e)
    {
        // $response = $e->getParam('response');
    }

    public function onAfterEmit(EventInterface $e)
    {
        // $response = $e->getParam('response');
    }
}