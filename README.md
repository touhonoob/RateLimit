[![Build Status](https://travis-ci.org/touhonoob/RateLimit.svg)](https://travis-ci.org/touhonoob/RateLimit/)
[![Code Climate](https://codeclimate.com/github/touhonoob/RateLimit/badges/gpa.svg)](https://codeclimate.com/github/touhonoob/RateLimit)
# RateLimit
PHP Rate Limiting Library With [Token Bucket Algorithm][wiki]

# Storage Adapters
- [APCu](https://pecl.php.net/package/APCu)
- [Redis](https://pecl.php.net/package/redis) or [Predis](https://github.com/nrk/predis)
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
composer.phar require touhonoob/rate-limit
````

# References
- [http://stackoverflow.com/a/668327/670662][stackoverflow]
- [http://en.wikipedia.org/wiki/Token_bucket][wiki]

[stackoverflow]: http://stackoverflow.com/a/668327/670662
[wiki]: http://en.wikipedia.org/wiki/Token_bucket
