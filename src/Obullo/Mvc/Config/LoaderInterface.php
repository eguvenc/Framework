<?php

namespace Obullo\Mvc\Config;

/**
 * Loader interface
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
interface LoaderInterface
{
	/**
	 * Load static files
	 * 
	 * @param  string  $filename filename
	 * @param  boolean $object   returns to zend config object
	 * 
	 * @return array|object
	 */
    public function load(string $filename, $object = false);
}
