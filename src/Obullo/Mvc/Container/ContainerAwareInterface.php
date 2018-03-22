<?php

namespace Obullo\Mvc\Container;

use Psr\Container\ContainerInterface;

/**
 * Immutable container aware interface
 */
interface ContainerAwareInterface
{
    /**
     * Set a container
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Get the container
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer();
}
