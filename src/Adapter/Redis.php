<?php

namespace PalePurple\RateLimit\Adapter;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
class Redis extends \PalePurple\RateLimit\Adapter
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
        return $this->redis->set($key, (string)$value, $ttl);
    }

    /**
     * @return float
     */
    public function get($key)
    {
        return (float)$this->redis->get($key);
    }

    public function exists($key)
    {
        return $this->redis->exists($key) == true;
    }

    public function del($key)
    {
        return $this->redis->del($key) > 0;
    }
}
