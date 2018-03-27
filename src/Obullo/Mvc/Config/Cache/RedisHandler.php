<?php

namespace Obullo\Mvc\Config\Cache;

use InvalidArgumentException;

/**
 * Redis
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class RedisHandler implements CacheInterface
{
    protected $redis;

    /**
     * Cache key prefix
     */
    const PREFIX = 'Mvc:config:';

    /**
     * Constructor
     *
     * $redis = new Redis(); 
     * $redis->connect('127.0.0.1', 6379); 
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
     * 
     * @param Redis $redis redis
     */
    public function __construct($redis)
    {
        if (!$redis instanceof \Redis && !$redis instanceof \RedisArray && !$redis instanceof \Predis\Client && !$redis instanceof RedisProxy) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s() expects parameter 1 to be Redis, RedisArray, RedisCluster or Predis\Client, %s given',
                    __METHOD__,
                    is_object($redis) ? get_class($redis) : gettype($redis)
                )
            );
        }
        $this->redis = $redis;
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
        if ($this->redis->exists($key)) {
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
        $data = $this->redis->hGetAll($key);
        $mtime = filemtime($file);
        $time = (int)$data['__mtime__'];
        if ($mtime > $time) {
            $this->redis->delete($key);
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
        $this->redis->hMSet($key, $data);
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