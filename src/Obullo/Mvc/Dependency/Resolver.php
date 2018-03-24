<?php

namespace Obullo\Mvc\Dependency;

use Obullo\Mvc\Container\{
    ContainerAwareTrait,
    ContainerAwareInterface
};
use ReflectionClass;
use Psr\Container\ContainerInterface as Container;
use Obullo\Mvc\Exception\UndefinedServiceException;

/**
 * Dependency resolver
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Resolver implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $reflection;
    protected $arguments = array();

    /**
     * Constructor
     * 
     * @param ReflectionClass $reflection reflection
     */
    public function __construct(ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Method parameters
     * 
     * @param array $args parameters
     */
    public function setArguments(array $params)
    {
        $this->arguments = $params;
    }

    /**
     * Resolve method parameters
     * 
     * @param  string $method method
     * @return array
     */
    public function resolve(string $method) : array
    {
        $injectedParameters = array();
        $parameters = $this->reflection->getMethod($method)->getParameters();
        foreach ($parameters as $param) {
            $name = $param->getName();
            $interface = $param->getClass();
            if ($interface) {
                if ($this->container->has($name)) {
                    $classInstance = $this->container->get($name);
                    $interfaceClass = $interface->getName();
                    if ($classInstance instanceof $interfaceClass) {
                        $injectedParameters[] = $classInstance;
                    }
                } else {
                    throw new UndefinedServiceException(
                        sprintf(
                            'The "%s" parameter of the "%s->%s()" is not defined in your container.',
                            $name,
                            $this->reflection->getName(),
                            $method
                        )
                    );
                }
            }
            if ($interface == null && isset($this->arguments[$name])) {
                $injectedParameters[] = $this->arguments[$name];
            }
        }
        return $injectedParameters;
    }
}