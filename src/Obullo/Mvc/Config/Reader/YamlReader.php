<?php

namespace Obullo\Mvc\Config\Reader;

use Zend\Config\Exception;
use Symfony\Component\Yaml\Yaml;
use Zend\Config\Reader\ReaderInterface;
use Obullo\Mvc\Config\Cache\CacheInterface as ConfigCache;

/**
 * YAML config reader for Zend\Config
 */
class YamlReader implements ReaderInterface
{
    /**
     * Config cache
     * 
     * @var object
     */
    protected $cache;

    /**
     * Directory of the YAML file
     *
     * @var string
     */
    protected $directory;

    /**
     * Constructor
     * 
     * @param ConfigCache $cache cache
     */
    public function __construct(ConfigCache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * fromFile(): defined by Reader interface.
     *
     * @see    ReaderInterface::fromFile()
     * @param  string $filename
     * @return array
     * @throws Exception\RuntimeException
     */
    public function fromFile($filename)
    {
        if (! is_file($filename) || ! is_readable($filename)) {
            throw new Exception\RuntimeException(sprintf(
                "File '%s' doesn't exist or not readable",
                $filename
            ));
        }
        $this->directory = dirname($filename);

        if ($this->cache->has($filename)) {
            $config = $this->cache->read($filename);
        } else {
            $config = Yaml::parse(file_get_contents($filename));
            $this->cache->write($filename, $config);
        }
        if (null === $config) {
            throw new Exception\RuntimeException("Error parsing YAML data");
        }

        return $this->process($config);
    }

    /**
     * fromString(): defined by Reader interface.
     *
     * @see    ReaderInterface::fromString()
     * @param  string $string
     * @return array|bool
     * @throws Exception\RuntimeException
     */
    public function fromString($string)
    {
        if (empty($string)) {
            return [];
        }
        $this->directory = null;

        $config = Yaml::parse($string);

        if (null === $config) {
            throw new Exception\RuntimeException("Error parsing YAML data");
        }

        return $this->process($config);
    }

    /**
     * Process the array for @include
     *
     * @param  array $data
     * @return array
     * @throws Exception\RuntimeException
     */
    protected function process(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->process($value);
            } else {
                $data[$key] = Self::parseEnvRecursive($value);
            }
            if (trim($key) === '@include') {
                if ($this->directory === null) {
                    throw new Exception\RuntimeException('Cannot process @include statement for a json string');
                }
                $reader = clone $this;
                unset($data[$key]);
                $data = array_replace_recursive($data, $reader->fromFile($this->directory . '/' . $value));
            }
        }
        return $data;
    }

    /**
     * Parse env
     * 
     * @param mixed $input input
     * @return string
     */
    protected static function parseEnvRecursive($input)
    {
        if (is_string($input)) {
            $input =  str_replace(['%ROOT%'],[ROOT],$input);
        }
        $regex = '/%env\((.*?)\)%/';
        if (is_array($input)) {
            $input = getenv($input[1]);
        }
        return preg_replace_callback($regex, 'Self::parseEnvRecursive', $input);
    }
}