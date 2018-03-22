<?php

namespace Obullo\Mvc\Config\Cache;

use Memcached;

/**
 * Memcached
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class MemcachedHandler implements CacheInterface
{
    protected $memcached;

    /**
     * Cache key prefix
     */
    const PREFIX = 'Mvc:config:';

    /**
     * Constructor
     *
     * $memcached = new Memcached; 
     * $memcached->addServer('127.0.0.1', 11211);
     * $memcached->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP);
     * 
     * @param Memcached $memcached memcached
     */
    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

	/**
	 * Checks the file has cached
	 * 
	 * @param  string $file filename
	 * @return boolean
	 */
    public function has(string $file) : bool
    {
        $key = Self::getKey($file);
        if ($this->memcached->get($key)) {
        	return true;
        }
        return false;
    }

    /**
     * Read file
     * 
     * @param  string $file file
     * @return string
     */
    public function read(string $file) : array
    {
        $key = Self::getKey($file);
        $data = $this->memcached->get($key);
        $mtime = filemtime($file);
		$time = (int)$data['__mtime__'];
		if ($mtime > $time) {
		    $this->memcached->delete($key);
		}
        unset($data['__mtime__']);
        return $data;
    }

    /**
     * Write to cache
     * 
     * @param  string $file  file
     * @param  data   $data  array
     * @return void
     */
    public function write(string $file, array $data)
    {
        $key = Self::getKey($file);
        $data['__mtime__'] = filemtime($file);
        $this->memcached->set($key, $data, 0);
    }

    /**
     * Returns to normalized key
     * 
     * @param  string $file file
     * @return string
     */
    protected static function getKey(string $file)
    {
        return Self::PREFIX.str_replace(ROOT, '', $file);
    }
}