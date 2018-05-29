<?php

use Zend\ServiceManager\ServiceManager;

class TranslatorFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testService()
    {
        $container = new ServiceManager;
        $container->setFactory('loader', 'Services\LoaderFactory');
        $container->setFactory('translator', 'Services\TranslatorFactory');

        $this->assertInstanceOf('Zend\I18n\Translator\Translator', $container->get('translator'));
    }
}