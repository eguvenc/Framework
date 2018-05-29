<?php

use Zend\ServiceManager\ServiceManager;

class EscaperFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testService()
	{
		$container = new ServiceManager;
		$container->setFactory('escaper', 'Services\EscaperFactory');

		$this->assertInstanceOf('Zend\Escaper\Escaper', $container->get('escaper'));
	}
}