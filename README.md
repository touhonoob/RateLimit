# RateLimit

[![Build Status](https://travis-ci.org/DavidGoodwin/RateLimit.svg)](https://travis-ci.org/DavidGoodwin/RateLimit/)

PHP Rate Limiting Library With Token Bucket Algorithm with minimal external dependencies.

# Installation

```composer require palepurple/rate-limit```

# Storage Adapters

The RateLimiter needs to know where to get/set data. 

Depending on which adapter you install, you may need to install additional libraries (predis/predis or tedivm/stash) or PHP extensions (e.g. Redis, Memcache, APC)


- [APCu](https://pecl.php.net/package/APCu)
- [Redis](https://pecl.php.net/package/redis) or [Predis](https://github.com/nrk/predis)
- [Stash](http://www.stashphp.com) (This supports many drivers - see http://www.stashphp.com/Drivers.html )
- [Memcached](http://php.net/manual/en/intro.memcached.php)


# Example
````php
require 'vendor/autoload.php';

use \PalePurple\RateLimit\RateLimit;
use \PalePurple\RateLimit\Adapter\APC as APCAdapter;
use \PalePurple\RateLimit\Adapter\Redis as RedisAdapter;
use \PalePurple\RateLimit\Adapter\Predis as PredisAdapter;
use \PalePurple\RateLimit\Adapter\Memcached as MemcachedAdapter;
use \PalePurple\RateLimit\Adapter\Stash as StashAdapter;


$adapter = new APCAdapter(); // Use APC as Storage
// Alternatives:
//
// $adapter = new RedisAdapter((new \Redis()->connect('localhost'))); // Use Redis as Storage
//
// $adapter = new PredisAdapter((new \Predis\Predis())->connect('localhost')); // Use Predis as Storage
//
// $memcache = new \Memcached();
// $memcache->addServer('localhost', 11211);
// $adapter = new MemcacheAdapter($memcache); 
//
// $stash = new \Stash\Pool(new \Stash\Driver\FileSystem());
// $adapter = new StashAdapter($stash);

$rateLimit = new RateLimit("myratelimit", 100, 3600, $adapter); // 100 Requests / Hour

$id = $_SERVER['REMOTE_ADDR']; // Use client IP as identity
if ($rateLimit->check($id)) {
  echo "passed";
} else {
  echo "rate limit exceeded";
}
````

# Installing via Composer
````shell
curl -sS https://getcomposer.org/installer | php
composer.phar require palepurple/rate-limit
````

# References

- [stackoverflow post about Rate Limiting](http://stackoverflow.com/a/668327/670662)
- [wikipedia token bucket](http://en.wikipedia.org/wiki/Token_bucket)
- [this code is forked from here...](https://github.com/touhonoob/RateLimit)
