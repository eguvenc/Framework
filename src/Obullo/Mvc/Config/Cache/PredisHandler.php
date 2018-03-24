<?php

namespace Obullo\Mvc\Config\Cache;

use Predis\Client;

/**
 * Predis client
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class PredisHandler implements CacheInterface
{
    protected $predis;

    /**
     * Cache key prefix
     */
    const PREFIX = 'Mvc:config:';

    /**
     * Constructor
     * 
     * $client = new \Predis\Client([
     *     'scheme' => 'tcp',
     *      'host'   => '127.0.0.1',
     *      'port'   => 6379,
     *   ]);
     * 
     * @param Client $predis predis
     */
    public function __construct(Client $predis)
    {
        $this->predis = $predis;
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
        if ($this->predis->exists($key)) {
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
        $data = $this->predis->hGetAll($key);
        $mtime = filemtime($file);
        $time = (int)$data['__mtime__'];
        if ($mtime > $time) {
            $this->predis->delete($key);
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
        $this->predis->hMSet($key, $data);
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