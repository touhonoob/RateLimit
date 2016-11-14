<?php

namespace Touhonoob\RateLimit\Adapter;

class RedisCustomClient extends Redis
{

    /**
     * RedisCustomClient constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }
}
