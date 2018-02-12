<?php

namespace Touhonoob\RateLimit\Adapter;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
class Redis extends \Touhonoob\RateLimit\Adapter
{

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * Redis constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function set($key, $value, $ttl)
    {
        return $this->redis->set($key, $value, $ttl);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function del($key)
    {
        return $this->redis->del($key);
    }
}
