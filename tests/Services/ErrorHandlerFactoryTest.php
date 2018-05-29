<?php

use Zend\ServiceManager\ServiceManager;

class ErrorHandlerFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');
		$container->setFactory('view', 'Services\ViewPlatesFactory');
		$container->setFactory('error', 'Services\ErrorHandlerFactory');
		$container->setFactory('translator', 'Services\TranslatorFactory');

		$this->assertInstanceOf('Obullo\Mvc\Error\ErrorHandler', $container->get('error'));
	}
}