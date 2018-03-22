<?php

namespace Obullo\Mvc\Container;

use Psr\Container\ContainerInterface;
use Obullo\Mvc\Exception\DefinedServiceException;

/**
 * Container proxy trait
 */
trait ContainerProxyTrait
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Set a container.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Returns to container
     * 
     * @return object
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * Container proxy:
     * Provides access to container variables from everywhere
     * 
     * @param string $key key
     *
     * @return null|object
     */
    public function __get(string $key)
    {
        if ($this->container->has($key)) {
            return $this->container->get($key);
        }
        return;
    }

    /**
     * We prevent to override container variables
     *
     * @param string $key string
     * @param string $val mixed
     *
     * @return void
     */
    public function __set(string $key, $val)
    {
        if ($this->container->has($key)) {
            throw new DefinedServiceException(
                sprintf(
                    'You can\'t set "%s" key as a variable. It\'s already defined in the container.',
                    $key
                )
            );
        }
        $this->{$key} = $val;
    }
}