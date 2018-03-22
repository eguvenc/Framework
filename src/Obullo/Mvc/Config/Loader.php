<?php

namespace Obullo\Mvc\Config;

/**
 * Config loader
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Loader implements LoaderInterface
{
    /**
     * Load static files
     * 
     * @param  string  $filename filename
     * @param  boolean $object   returns to zend config object
     * 
     * @return array|object
     */
    public function load(string $filename, $object = false)
    {
        $path = str_replace('%env%', getenv('APP_ENV'), $filename);

        return \Zend\Config\Factory::fromFile(ROOT.$path, $object);
    }
}