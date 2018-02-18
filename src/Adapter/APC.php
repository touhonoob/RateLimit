<?php

namespace PalePurple\RateLimit\Adapter;

use PalePurple\RateLimit\Adapter;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
class APC extends Adapter
{
    public function set($key, $value, $ttl)
    {
        return apc_store($key, $value, $ttl);
    }

    public function get($key)
    {
        return apc_fetch($key);
    }

    public function exists($key)
    {
        return apc_exists($key);
    }

    public function del($key)
    {
        return apc_delete($key);
    }
}
