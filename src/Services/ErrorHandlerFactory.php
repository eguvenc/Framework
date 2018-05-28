<?php

namespace Services;

use Obullo\Mvc\Error\{
    ErrorHandler,
    HtmlStrategy
};
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorHandlerFactory implements FactoryInterface
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
		$error = new ErrorHandler;
		$error->setContainer($container);

        $strategy = new HtmlStrategy($container->get('view'));
        $strategy->setTranslator($container->get('translator'));
		$error->setResponseStrategy($strategy);

        return $error;
    }
}