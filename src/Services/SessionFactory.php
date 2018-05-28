<?php

namespace Services;

use Zend\Session\SessionManager;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Storage\SessionArrayStorage;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SessionFactory implements FactoryInterface
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
        $framework = $container->get('loader')
            ->loadEnvConfigFile('framework.yaml', true)
            ->framework;

        $manager = new SessionManager();
        $manager->setStorage(new SessionArrayStorage());
        $manager->getValidatorChain()
            ->attach('session.validate', [new HttpUserAgent(), 'isValid']);
        $manager->setName($framework->session->name);
        $manager->start();

        return $manager;
    }
}