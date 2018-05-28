<?php

use Zend\ServiceManager\ServiceManager;

class CookieFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('cookie', 'Services\CookieFactory');
		$container->setFactory('request', 'Services\RequestFactory');

		$this->assertInstanceOf('Obullo\Mvc\Http\Cookie', $container->get('cookie'));
	}

	public function testServiceParameters()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('cookie', 'Services\CookieFactory');
		$container->setFactory('request', 'Services\RequestFactory');

		$parameters = $container->get('cookie')
			->getDefaults();

		$this->assertEquals('', $parameters['domain']);
		$this->assertEquals('/', $parameters['path']);
		$this->assertEquals(false, $parameters['secure']);
		$this->assertEquals(true, $parameters['httpOnly']);
		$this->assertEquals(0, $parameters['expire']);
	}
}