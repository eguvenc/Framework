<?php

namespace Obullo\Mvc\Config;

use Obullo\Mvc\Config\LoaderInterface as Loader;

trait LoaderAwareTrait
{
    /**
     * Loader
     *
     * @var object
     */
    protected $loader;

    /**
     * Set loader
     *
     * @param object $config config
     *
     * @return $this
     */
    public function setLoader(Loader $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Get loader
     *
     * @return object
     */
    public function getLoader() : Loader
    {
        return $this->loader;
    }
}
