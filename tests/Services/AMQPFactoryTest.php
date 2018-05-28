<?php

use Zend\ServiceManager\ServiceManager;

class AMQPFactoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('amqp', 'Services\AMQPFactory');

		$this->container = $container;
	}

	public function testAMQPConnection()
	{
		$this->assertInstanceOf('AMQPConnection', $this->container->get('amqp'));
	}
}
