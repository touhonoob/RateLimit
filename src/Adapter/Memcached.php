<?php

namespace Touhonoob\RateLimit\Adapter;

class Memcached extends \Touhonoob\RateLimit\Adapter
{

    /**
     * @var \Memcached
     */
    protected $memcached;

    # https://github.com/websoftwares/Throttle/blob/master/src/Websoftwares/Storage/Memcached.php#L25
    public function __construct(array $servers = [])
    {
        $servers = array_merge(['127.0.0.1' => 11211], $servers);
        $memcached = new \Memcached();
        foreach($servers as $server => $port) {
            $memcached->addServer($server, $port);
        }
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
        return (bool)$this->get($key);
    }

    public function del($key)
    {
        return $this->memcached->delete($key);
    }

}
