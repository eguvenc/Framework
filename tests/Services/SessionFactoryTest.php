<?php

use Zend\ServiceManager\ServiceManager;

class SessionFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('session', 'Services\SessionFactory');

		$manager = $container->get('session');
		$manager->start();

		$_SESSION['test'] = 'value';
		$this->assertEquals('value', $_SESSION['test']);

		unset($_SESSION['test']);
	}
}