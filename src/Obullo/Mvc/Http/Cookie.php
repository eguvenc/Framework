<?php

namespace Obullo\Mvc\Http;

use Obullo\Mvc\Exception\BadCookieException;

/**
 * Cookie
 *
 * @copyright 2018 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Cookie implements CookieInterface
{
    /**
     * Cookie unique name
     *
     * @var string
     */
    protected $name;
    
    /**
     * Request cookies
     *
     * @var array
     */
    protected $requestCookies = array();

    /**
     * Response cookies
     *
     * @var array
     */
    protected $responseCookies = array();

    /**
     * Default cookie properties
     *
     * @var array
     */
    protected $defaults = [
        'value' => '',
        'domain' => null,
        'path' => null,
        'secure' => false,
        'httpOnly' => false,
        'expire' => null
    ];

    /**
     * Create new cookies helper
     *
     * @param array $cookies
     */
    public function __construct(array $cookies = [])
    {
        $this->requestCookies = $cookies;
    }

    /**
     * Set default cookie properties
     *
     * @param array $settings
     */
    public function setDefaults(array $settings)
    {
        $this->defaults = array_replace($this->defaults, $settings);
    }

    /**
     * Returns to default settings
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Set cookie name
     *
     * @param string $name cookie name
     *
     * @return object
     */
    public function name(string $name)
    {
        $this->name = trim($name);
        return $this;
    }
    
    /**
     * Set cookie value
     *
     * @param string $value value
     *
     * @return object
     */
    public function value($value = '')
    {
        $this->validateName();
        $this->responseCookies[$this->name]['value'] = $value;
        return $this;
    }

    /**
     * Set cookie expire in seconds
     *
     * @param integer $expire seconds
     *
     * @return object
     */
    public function expire($expire = 0)
    {
        $this->validateName();
        $this->responseCookies[$this->name]['expire'] = $this->getExpiration($expire);
        return $this;
    }

    /**
     * Set cookie domain name
     *
     * @param string $domain name
     *
     * @return void
     */
    public function domain($domain = '')
    {
        $this->validateName();
        $this->responseCookies[$this->name]['domain'] = $domain;
        return $this;
    }

    /**
     * Set cookie path
     *
     * @param string $path name
     *
     * @return object
     */
    public function path($path = '/')
    {
        $this->validateName();
        $this->responseCookies[$this->name]['path'] = $path;
        return $this;
    }

    /**
     * Set secure cookie
     *
     * @param boolean $bool true or false
     *
     * @return object
     */
    public function secure($bool = false)
    {
        $this->validateName();
        $this->responseCookies[$this->name]['secure'] = $bool;
        return $this;
    }

    /**
     * Make cookie available just for http. ( No javascript )
     *
     * @param boolean $bool true or false
     *
     * @return object
     */
    public function httpOnly($bool = false)
    {
        $this->validateName();
        $this->responseCookies[$this->name]['httpOnly'] = $bool;
        return $this;
    }

    /**
     * Cookie set helper function to build default values
     *
     * @param string $name mixed name or parameters
     * @param mixed  $value value
     *
     * @return object
     */
    public function set(string $name, $value)
    {
        $this->name($name);
        if ($value != null) {
            $this->value($value);
        }
        $this->buildDefaultValues();
        return $this;
    }

    /**
     * Get request cookie
     *
     * @param string $name    cookie name
     * @param string $default default value
     *
     * @return string sanitized cookie
     */
    public function get(string $name, $default = null)
    {
        return isset($this->requestCookies[$name]) ? $this->requestCookies[$name] : $default;
    }

    /**
     * Build default parameters
     *
     * @return array
     */
    public function buildDefaultValues()
    {
        foreach (array('value','domain','path','expire','secure','httpOnly') as $key) {
            if (! isset($this->responseCookies[$this->name][$key]) && array_key_exists($key, $this->defaults)) {
                $this->responseCookies[$this->name][$key] = $this->defaults[$key];
            }
        }
    }

    /**
     * Convert to array
     *
     * @return string[]
     */
    public function toArray()
    {
        return $this->responseCookies;
    }

    /**
     * Convert to `Set-Cookie` headers
     *
     * @return string[]
     */
    public function toHeaders()
    {
        $headers = [];
        foreach ($this->responseCookies as $name => $properties) {
            $headers[] = $this->toString($name, $properties);
        }
        return $headers;
    }

    /**
     * Convert to `Set-Cookie` header
     *
     * @param string $name       Cookie name
     * @param array  $properties Cookie properties
     *
     * @return string
     */
    public function toString($name, array $properties)
    {
        $result = urlencode($name) . '=' . urlencode($properties['value']);

        if (isset($properties['domain'])) {
            $result .= '; domain=' . $properties['domain'];
        }

        if (isset($properties['path'])) {
            $result .= '; path=' . $properties['path'];
        }
        $timestamp = $this->getTimestamp($properties);

        if ($timestamp !== 0) {
            $result .= '; expires=' . gmdate('D, d-M-Y H:i:s e', $timestamp);
        }

        if (isset($properties['secure']) && $properties['secure']) {
            $result .= '; secure';
        }

        if (isset($properties['httpOnly']) && $properties['httpOnly']) {
            $result .= '; HttpOnly';
        }
        return $result;
    }

    /**
     * Create timestamp
     *
     * @param array $properties cookie properties
     *
     * @return int
     */
    protected function getTimestamp(array $properties) : int
    {
        $timestamp = 0;
        if (isset($properties['expire'])) {
            if (is_string($properties['expire'])) {
                $timestamp = strtotime($properties['expire']);
            } else {
                $timestamp = (int)$properties['expire'];
            }
        }
        return $timestamp;
    }

    /**
     * Get expiration of cookie
     *
     * @param int $expire in second
     *
     * @return int
     */
    protected function getExpiration($expire) : int
    {
        if ($expire == '0' || $expire == 0) {
            return 0;
        }
        if (! is_numeric($expire)) {
            $expire = time() - 86500;
        } else {
            $expire = time() + $expire;
        }
        return (int)$expire;
    }

    /**
    * Delete a cookie
    *
    * @param string|array $name cookie
    *
    * @return object
    */
    public function delete($name = null)
    {
        $this->name($name);
        $this->value(null)->expire(-1);
        return $this;
    }

    /**
     * Validate cookie name
     *
     * @return void
     */
    protected function validateName()
    {
        if (empty($this->name)) {
            throw new BadCookieException(
                'You must set a cookie name at first.'
            );
        }
    }
}