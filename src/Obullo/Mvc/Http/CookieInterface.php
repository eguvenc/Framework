<?php

namespace Obullo\Mvc\Http;

/**
 * Cookie Interface
 *
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
interface CookieInterface
{
    /**
     * Set cookie name
     *
     * @param string $name cookie name
     *
     * @return object
     */
    public function name(string $name);
    
    /**
     * Set cookie value
     *
     * @param string $value value
     *
     * @return object
     */
    public function value($value = '');

    /**
     * Set cookie expire in seconds
     *
     * @param integer $expire seconds
     *
     * @return object
     */
    public function expire($expire = 0);

    /**
     * Set cookie domain name
     *
     * @param string $domain name
     *
     * @return void
     */
    public function domain($domain = '');

    /**
     * Set cookie path
     *
     * @param string $path name
     *
     * @return object
     */
    public function path($path = '/');

    /**
     * Set secure cookie
     *
     * @param boolean $bool true or false
     *
     * @return object
     */
    public function secure($bool = false);

    /**
     * Make cookie available just for http. ( No javascript )
     *
     * @param boolean $bool true or false
     *
     * @return object
     */
    public function httpOnly($bool = false);

    /**
     * Cookie set helper function to build default values
     *
     * @param string $name mixed name or parameters
     * @param mixed  $value value
     *
     * @return void
     */
    public function set(string $name, $value);
    
    /**
     * Get cookie
     *
     * @param string $name    cookie name
     * @param string $default default value
     *
     * @return string sanitized cookie
     */
    public function get(string $name, $default = null);
    
    /**
    * Delete a cookie
    *
    * @param string $name cookie
    *
    * @return void
    */
    public function delete($name = null);
}