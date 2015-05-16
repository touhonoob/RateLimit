[![Build Status](https://travis-ci.org/touhonoob/RateLimit.svg)](https://travis-ci.org/touhonoob/RateLimit/)
[![Code Climate](https://codeclimate.com/github/touhonoob/RateLimit/badges/gpa.svg)](https://codeclimate.com/github/touhonoob/RateLimit)
# RateLimit
Rate Limiting Library With Token Bucket Algorithm

Reference: http://stackoverflow.com/a/668327/670662
# Example
````php
use \Touhonoob\RateLimit\RateLimit;
use \Touhonoob\RateLimit\Adapter\APC as RateLimitAdapterAPC;
use \Touhonoob\RateLimit\Adapter\Redis as RateLimitAdapterRedis;

$adapter = new RateLimitAdapterAPC(); // Use APC as Storage
// $adapter = new RateLimitAdapterRedis(); // Use Redis as Storage
$rateLimit = new RateLimit("myratelimit", 100, 3600, $adapter); // 100 Requests / Hour

$ip = $_SERVER['REMOTE_ADDR'];
if ($rateLimit->check($ip)) {
  echo "passed";
} else {
  echo "rate limit exceeded";
}
````
