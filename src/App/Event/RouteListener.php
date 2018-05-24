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
use Obullo\Router\RouteCollection;
use Obullo\Router\Types\{
    StrType,
    IntType,
    TranslationType
};
class RouteListener implements ListenerAggregateInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = null)
    {
        $this->listeners[] = $events->attach('route.types', [$this, 'onRouteTypes']);
        $this->listeners[] = $events->attach('route.builder', [$this, 'onRouteBuilder']);
        $this->listeners[] = $events->attach('route.match', [$this, 'onMatch']);
    }

    public function onRouteTypes(EventInterface $e) : array
    {
        return [
            new IntType('<int:id>'),
            new IntType('<int:page>'),
            new StrType('<str:name>'),
            new TranslationType('<locale:locale>'),
        ];
    }

    public function onRouteBuilder(EventInterface $e) : RouteCollection
    {
        $builder = $e->getParam('builder');
        $routes  = $e->getParam('routes');
        
        return $builder->build($routes);        
    }

    public function onMatch(EventInterface $e)
    {
        /*
        $route = $e->getParams();
        $route->getName();
        */
    }
}
