<?php

namespace DavidGoodwin\RateLimit;

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
     * @var Adapter
     */
    private $adapter;

    /**
     * RateLimit constructor.
     * @param string $name - prefix used in storage keys.
     * @param int $maxRequests
     * @param int $period seconds
     * @param Adapter $adapter - storage adapter
     */
    public function __construct($name, $maxRequests, $period, Adapter $adapter)
    {
        $this->name = $name;
        $this->maxRequests = $maxRequests;
        $this->period = $period;
        $this->ttl = $this->period;
        $this->adapter = $adapter;
    }

    /**
     * Rate Limiting
     * http://stackoverflow.com/a/668327/670662
     * @param string $id
     * @param float $use
     * @return boolean
     */
    public function check($id, $use = 1.0)
    {
        $rate = $this->maxRequests / $this->period;

        $t_key = $this->keyTime($id);
        $a_key = $this->keyAllow($id);

        if (!$this->adapter->exists($t_key)) {
            // first hit; setup storage; allow.
            $this->adapter->set($t_key, time(), $this->ttl);
            $this->adapter->set($a_key, ($this->maxRequests - $use), $this->ttl);
            return true;
        }

        $c_time = time();

        $time_passed = $c_time - $this->adapter->get($t_key);
        $this->adapter->set($t_key, $c_time, $this->ttl);

        $allowance = $this->adapter->get($a_key);
        $allowance += $time_passed * $rate;

        if ($allowance > $this->maxRequests) {
            $allowance = $this->maxRequests; // throttle
        }


        if ($allowance < $use) {
            // need to wait for more 'tokens' to be in the bucket.
            $this->adapter->set($a_key, $allowance, $this->ttl);
            return false;
        }


        $this->adapter->set($a_key, $allowance - $use, $this->ttl);
        return true;

    }

    /**
     * @deprecated use getAllowance() instead.
     * @param string $id
     * @return int
     */
    public function getAllow($id) {
        return $this->getAllowance($id);
    }


    /**
     * Get allowance left.
     *
     * @param string $id
     * @return int number of requests that can be made before hitting a limit.
     */
    public function getAllowance($id)
    {
        $this->check($id, 0.0);

        $a_key = $this->keyAllow($id);

        if (!$this->adapter->exists($a_key)) {
            return $this->maxRequests;
        } else {
            return max(0, floor($this->adapter->get($a_key)));
        }
    }

    /**
     * Purge rate limit record for $id
     * @param string $id
     */
    public function purge($id)
    {
        $this->adapter->del($this->keyTime($id));
        $this->adapter->del($this->keyAllow($id));
    }

    private function keyTime($id)
    {
        return $this->name . ":" . $id . ":time";
    }

    private function keyAllow($id)
    {
        return $this->name . ":" . $id . ":allow";
    }
}
