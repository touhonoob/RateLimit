<?php

namespace Touhonoob\RateLimit;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
abstract class Adapter
{
    /**
     * @return bool
     */
    abstract public function set($key, $value, $ttl);
    
    /**
     * @return bool
     */
    abstract public function get($key);
    
    /**
     * @return bool
     */
    abstract public function exists($key);
    
    /**
     * @return bool
     */
    abstract public function del($key);
}
