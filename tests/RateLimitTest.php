<?php

namespace Touhonoob\RateLimit\Tests;

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

    public function testCheckAPC() {
        $adapter = new \Touhonoob\RateLimit\Adapter\APC();
        $this->check($adapter);
    }
    
    public function testCheckRedis() {
        $adapter = new \Touhonoob\RateLimit\Adapter\Redis();
        $this->check($adapter);
    }
    
    private function check($adapter)
    {
        $ip = "127.0.0.1";
        $rateLimit = new RateLimit(self::NAME, self::MAX_REQUESTS, self::PERIOD, $adapter);
        $rateLimit->ttl = 100;
        
        //First
        $this->assertTrue($rateLimit->check($ip));
        
        //Repeat MAX_REQUESTS - 1 times
        for($i = 0;$i < self::MAX_REQUESTS ;$i++) {
            $this->assertTrue($rateLimit->check($ip));
        }
        
        //MAX_REQUESTS + 1
        $this->assertFalse($rateLimit->check($ip));
        
        //Wait for PERIOD seconds
        sleep(self::PERIOD);
        $this->assertTrue($rateLimit->check($ip));
        
        $rateLimit->purge($ip);
    }
}
