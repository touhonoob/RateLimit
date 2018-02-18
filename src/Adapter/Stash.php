<?php

namespace PalePurple\RateLimit\Adapter;

use PalePurple\RateLimit\Adapter;
use Stash\Invalidation;

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
        $item->setInvalidationMethod(Invalidation::OLD);

        if ($item->isHit()) {
            return $item->get();
        }
        return null;
    }

    public function set($key, $value, $ttl)
    {
        $item = $this->pool->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl);
        return $item->save();
    }

    public function exists($key)
    {
        $item = $this->pool->getItem($key);
        $item->setInvalidationMethod(Invalidation::OLD);
        return $item->isHit();
    }

    public function del($key)
    {
        $this->pool->deleteItem($key);
    }
}
