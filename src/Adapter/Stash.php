<?php

namespace DavidGoodwin\RateLimit\Adapter;


use DavidGoodwin\RateLimit\Adapter;

class Stash extends Adapter
{

    /**
     * @var \Stash\Pool
     */
    private $pool;

    public function __construct(\Stash\Pool $pool)
    {
        $this->pool = $pool;
    }

    public function get($key)
    {
        $item = $this->pool->getItem($key);
        if ($item->isHit()) {
            return $item->get();
        }
        return null;
    }

    public function set($key, $value, $ttl)
    {
        $item = $this->pool->getItem($key);
        $item->setTtl($ttl);
        $item->set($value);
        $item->save();
    }

    public function exists($key)
    {
        return $this->pool->hasItem($key);
    }

    public function del($key)
    {
        $this->pool->deleteItem($key);
    }
}