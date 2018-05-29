<?php

use Zend\ServiceManager\ServiceManager;

class MongoFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $container = new ServiceManager;
        $container->setFactory('loader', 'Services\LoaderFactory');
        $container->setFactory('mongo', 'Services\MongoFactory');

        $this->assertInstanceOf('MongoDB\Driver\Manager', $container->get('mongo'));
    }
}