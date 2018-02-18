<?php

namespace PalePurple\RateLimit\Adapter;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date June 7, 2016
 */
class APCu extends \PalePurple\RateLimit\Adapter
{
    public function set($key, $value, $ttl)
    {
        return apcu_store($key, $value, $ttl);
    }

    public function get($key)
    {
        return apcu_fetch($key);
    }

    public function exists($key)
    {
        return apcu_exists($key);
    }

    public function del($key)
    {
        return apcu_delete($key);
    }
}
