<?php

use Zend\ServiceManager\ServiceManager;

class LoaderFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('loader', 'Services\LoaderFactory');

		$this->assertInstanceOf('Obullo\Mvc\Config\Loader\YamlLoader', $container->get('loader'));
	}
}