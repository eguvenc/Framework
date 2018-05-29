<?php

use Zend\ServiceManager\ServiceManager;

class RedisFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $container = new ServiceManager;
        $container->setFactory('loader', 'Services\LoaderFactory');
        $container->setFactory('redis', 'Services\RedisFactory');

        $this->assertInstanceOf('Redis', $container->get('redis'));
    }
}