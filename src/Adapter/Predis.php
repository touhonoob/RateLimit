<?php

namespace PalePurple\RateLimit\Adapter;

/**
 * Predis adapter
 */
class Predis extends \PalePurple\RateLimit\Adapter
{

    /**
     * @var \Predis\ClientInterface
     */
    protected $redis;

    public function __construct(\Predis\ClientInterface $client)
    {
        $this->redis = $client;
    }


    public function set($key, $value, $ttl)
    {
        return $this->redis->set($key, (string) $value, "ex", $ttl);
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
        return (bool)$this->redis->exists($key);
    }

    public function del($key)
    {
        return (bool)$this->redis->del([$key]);
    }
}
