<?php

use Zend\ServiceManager\ServiceManager;

class EventManagerFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('events', 'Services\EventManagerFactory');

		$this->assertInstanceOf('Zend\EventManager\EventManager', $container->get('events'));
	}
}