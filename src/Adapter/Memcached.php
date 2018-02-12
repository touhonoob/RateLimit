<?php

namespace PalePurple\RateLimit\Adapter;

class Memcached extends \PalePurple\RateLimit\Adapter
{

    /**
     * @var \Memcached
     */
    protected $memcached;

    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function set($key, $value, $ttl)
    {
        return $this->memcached->set($key, $value, $ttl);
    }

    public function get($key)
    {
        return $this->memcached->get($key);
    }

    public function exists($key)
    {
        return $this->get($key) !== false;

    }

    public function del($key)
    {
        return $this->memcached->delete($key);
    }

}
