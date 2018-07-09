<?php

namespace PalePurple\RateLimit\Adapter;

/**
 * Predis adapter
 */
class Predis extends Redis
{

    /**
     * @var \Predis\ClientInterface
     */
    protected $redis;

    public function __construct(\Predis\ClientInterface $client)
    {
        $this->redis = $client;
    }

    /**
     * @param string $key
     * @param int $value
     * @param int $ttl seconds
     * @return bool
     */
    public function set($key, $value, $ttl)
    {
        return $this->redis->set($key, $value, "ex", $ttl);
    }
}
