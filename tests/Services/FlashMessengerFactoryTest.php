<?php

use Zend\ServiceManager\ServiceManager;

class FlashMessengerFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('flash', 'Services\FlashMessengerFactory');
		$container->setFactory('escaper', 'Services\EscaperFactory');
		$container->setFactory('session', 'Services\SessionFactory');

		$manager = $container->get('session');
		$manager->start();

		$this->assertInstanceOf('Obullo\Mvc\Session\FlashMessenger', $container->get('flash'));
	}
}