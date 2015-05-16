<?php

namespace Touhonoob\RateLimit;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
class RateLimit
{

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var int
     */
    public $maxRequests;

    /**
     *
     * @var int
     */
    public $period;

    /**
     *
     * @var int
     */
    public $ttl;

    /**
     *
     * @var Adapter
     */
    private $adapter;

    public function __construct($name, $maxRequests, $period, $adapter)
    {
        $this->name = $name;
        $this->maxRequests = $maxRequests;
        $this->period = $period;
        $this->ttl = $this->period * 2;
        $this->adapter = $adapter;
    }

    /**
     * Rate Limiting
     * http://stackoverflow.com/a/668327/670662
     * @param string $ip
     * @return boolean
     */
    public function check($ip)
    {
        $rate = $this->maxRequests / $this->period;

        $t_key = $this->keyTime($ip);
        $a_key = $this->keyAllow($ip);

        if ($this->adapter->exists($t_key)) {
            $c_time = time();

            $time_passed = $c_time - $this->adapter->get($t_key);
            $this->adapter->set($t_key, $c_time, $this->ttl);

            $allow = $this->adapter->get($a_key);
            $allow += $time_passed * $rate;

            if ($allow > $this->maxRequests) {
                $allow = $this->maxRequests;
            }

            if ($allow < 1.0) {
                $this->adapter->set($a_key, $allow, $this->ttl);
                return false;
            } else {
                $allow -= 1.0;
                $this->adapter->set($a_key, $allow, $this->ttl);
                return true;
            }
        } else {
            $this->adapter->set($t_key, time(), $this->ttl);
            $this->adapter->set($a_key, $this->maxRequests, $this->ttl);
            return true;
        }
    }

    /**
     * Purge rate limit record for $ip
     * @param string $ip
     */
    public function purge($ip)
    {
        $this->adapter->del($this->keyTime($ip));
        $this->adapter->del($this->keyAllow($ip));
    }

    public function keyTime($ip)
    {
        return $this->name . ":" . $ip . ":time";
    }

    public function keyAllow($ip)
    {
        return $this->name . ":" . $ip . ":allow";
    }
}
