<?php

namespace Obullo\Mvc\Container;

use Psr\Container\ContainerInterface;

/**
 * Immutable container aware trait
 */
trait ContainerAwareTrait
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
}
