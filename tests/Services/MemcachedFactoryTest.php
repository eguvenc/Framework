<?php

use Zend\ServiceManager\ServiceManager;

class MemcachedFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $container = new ServiceManager;
        $container->setFactory('loader', 'Services\LoaderFactory');
        $container->setFactory('memcached', 'Services\MemcachedFactory');

        $this->assertInstanceOf('Memcached', $container->get('memcached'));
    }
}