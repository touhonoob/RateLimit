<?php

namespace Touhonoob\RateLimit\Tests;

use Touhonoob\RateLimit\Adapter;
use Touhonoob\RateLimit\RateLimit;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date May 16, 2015
 */
class RateLimitTest extends \PHPUnit_Framework_TestCase
{

    const NAME = "RateLimitTest";
    const MAX_REQUESTS = 10;
    const PERIOD = 3;

    /**
     * @requires extension apc
     */
    public function testCheckAPC()
    {
        $adapter = new \Touhonoob\RateLimit\Adapter\APC();
        $this->check($adapter);
    }

    /**
     * @requires extension apcu
     */
    public function testCheckAPCu()
    {
        $adapter = new \Touhonoob\RateLimit\Adapter\APCu();
        $this->check($adapter);
    }

    /**
     * @requires extension redis
     */ 
    public function testCheckRedis()
    {
        $adapter = new \Touhonoob\RateLimit\Adapter\Redis();
        $this->check($adapter);
    }

    public function testCheckMemcached()
    {
        if (!extension_loaded('memcached')) {
            $this->markTestSkipped("memcached extension not installed");
        }
        $adapter = new \Touhonoob\RateLimit\Adapter\Memcached();
        $this->check($adapter);
    }


    public function testCheckRedisCustomClient()
    {
        $redis = new \Redis();
        $redis->pconnect('127.0.0.1', 6379);
        $adapter = new \Touhonoob\RateLimit\Adapter\RedisCustomClient($redis);
        $this->check($adapter);
    }


    public function testCheckPredisClient() {
        if(!class_exists(\Predis\Client::class)) {
            $this->markTestSkipped("no predis/predis support");
        }
        $predis = new \Predis\Client(); // assumes localhost:6379
        $adapter = new \Touhonoob\RateLimit\Adapter\Predis($predis);
        $this->check($adapter);
    }

    private function check($adapter)
    {
        $ip = "127.0.0.1";
        $rateLimit = $this->getRateLimit($adapter);
        $rateLimit->ttl = 100;

        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->getAllow($ip));

        //First
        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->check($ip));

        //Repeat MAX_REQUESTS - 1 times
        for ($i = 0; $i < self::MAX_REQUESTS; $i++) {
            $this->assertEquals(self::MAX_REQUESTS - $i - 1, $rateLimit->getAllow($ip));
            $this->assertEquals(self::MAX_REQUESTS - $i - 1, $rateLimit->check($ip));
        }

        //MAX_REQUESTS + 1
        $this->assertEquals(0, $rateLimit->getAllow($ip));
        $this->assertEquals(0, $rateLimit->check($ip));

        //Wait for PERIOD seconds
        sleep(self::PERIOD);
        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->getAllow($ip));
        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->check($ip));

        $rateLimit->purge($ip);
    }

    private function getRateLimit(Adapter $adapter)
    {
        return new RateLimit(self::NAME, self::MAX_REQUESTS, self::PERIOD, $adapter);
    }
}
