<?php

use Zend\ServiceManager\ServiceManager;

class LoggerFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('logger', 'Services\LoggerFactory');

		$this->assertInstanceOf('Monolog\Logger', $container->get('logger'));
	}
}