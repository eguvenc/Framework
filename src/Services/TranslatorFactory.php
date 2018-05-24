<?php

namespace Services;

use Zend\I18n\Translator\Resources;
use Zend\I18n\Translator\Translator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TranslatorFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $container->setAlias('MvcTranslator', $requestedName); // Zend framework support

        $config = $container->get('loader')
            ->loadEnvConfigFile('framework.yaml', true)
            ->framework
            ->translator;
            
        $translator = new Translator;
        $translator->setLocale($config->default_locale);
		return $translator;
    }
}