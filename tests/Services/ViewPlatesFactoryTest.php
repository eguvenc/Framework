<?php

use Zend\ServiceManager\ServiceManager;

class ViewPlatesFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $container = new ServiceManager;
        $container->setFactory('loader', 'Services\LoaderFactory');
        $container->setFactory('view', 'Services\ViewPlatesFactory');

        $this->assertInstanceOf('Obullo\Mvc\View\PlatesPhp', $container->get('view'));
    }
}