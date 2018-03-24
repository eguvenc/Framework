<?php

namespace Obullo\Mvc\Config\Cache;

interface CacheInterface
{
    /**
     * Checks the file has cached
     * 
     * @param  string $file filename
     * @return boolean
     */
    public function has(string $file) : bool;

    /**
     * Read file
     * 
     * @param  string $file file
     * @return string
     */
    public function read(string $file) : array;

    /**
     * Write to cache
     * 
     * @param  string $file  file
     * @param  data   $data  array
     * @return void
     */
    public function write(string $file, array $data);
}