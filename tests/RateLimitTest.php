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
        if (!extension_loaded('apc')) {
            $this->markTestSkipped("apc not installed");
        }
        if (ini_get('apc.enable_cli') == 0) {
            $this->markTestSkipped("apc.enable_cli != 1; can't change at runtime");
        }
        $adapter = new \Touhonoob\RateLimit\Adapter\APC();
        $this->check($adapter);
    }

    /**
     * @requires extension apcu
     */
    public function testCheckAPCu()
    {
        if (!extension_loaded('apcu')) {
            $this->markTestSkipped("apcu not installed");
        }
        if (ini_get('apc.enable_cli') == 0) {
            $this->markTestSkipped("apc.enable_cli != 1; can't change at runtime");
        }
        $adapter = new \Touhonoob\RateLimit\Adapter\APCu();
        $this->check($adapter);
    }

    /**
     * @requires extension redis
     */
    public function testCheckRedis()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped("Redis not installed");
        }
        $adapter = new \Touhonoob\RateLimit\Adapter\Redis();
        $this->check($adapter);
    }

    public function testCheckRedisCustomClient()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped("redis extension not installed");
        }
        $redis = new \Redis();
        $redis->pconnect('127.0.0.1', 6379);
        $adapter = new \Touhonoob\RateLimit\Adapter\RedisCustomClient($redis);
        $this->check($adapter);
    }

    public function testCheckPredisClient()
    {
        if (!class_exists(\Predis\Client::class)) {

            $this->markTestSkipped("no predis/predis?");
        }

        $predis = new \Predis\Client(); // assumes localhost:6379
        $adapter = new \Touhonoob\RateLimit\Adapter\Predis($predis);
        $this->check($adapter);
    }

    private function check($adapter)
    {
        $label = uniqid();
        $rateLimit = $this->getRateLimit($adapter);
        $rateLimit->ttl = 100;

        $rateLimit->purge($label); // incase the previous test failed and our storage is dirty.


        //Repeat MAX_REQUESTS - 1 times (all should work, but bucket should be empty at the end)
        for ($i = 0; $i < self::MAX_REQUESTS; $i++) {
            // calling check reduces the counter each time.
            $this->assertEquals((self::MAX_REQUESTS - $i), $rateLimit->getAllow($label));
            $this->assertTrue($rateLimit->check($label));
        }

        $this->assertEquals(0, $rateLimit->getAllow($label));
        $this->assertFalse($rateLimit->check($label));


        //Wait for PERIOD seconds, bucket should refill
        sleep(self::PERIOD);

        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->getAllow($label));
        $this->assertEquals(self::MAX_REQUESTS, $rateLimit->check($label));

    }

    private function getRateLimit(Adapter $adapter)
    {
        return new RateLimit(self::NAME, self::MAX_REQUESTS, self::PERIOD, $adapter);
    }
}
