[![Build Status](https://travis-ci.org/touhonoob/RateLimit.svg)](https://travis-ci.org/touhonoob/RateLimit/)
[![Code Climate](https://codeclimate.com/github/touhonoob/RateLimit/badges/gpa.svg)](https://codeclimate.com/github/touhonoob/RateLimit)
# RateLimit
PHP Rate Limiting Library With [Token Bucket Algorithm][wiki]

# Storage Adapters
- [APCu](https://pecl.php.net/package/APCu)
- [Redis](https://pecl.php.net/package/redis) or [Predis](https://github.com/nrk/predis)

# Example

````php
require 'vendor/autoload.php';

use \Touhonoob\RateLimit\RateLimit;
use \Touhonoob\RateLimit\Adapter\APC as RateLimitAdapterAPC;
use \Touhonoob\RateLimit\Adapter\Redis as RateLimitAdapterRedis;
use \Touhonoob\RateLimit\Adapter\Redis as RateLimitAdapterPredis;

$adapter = new RateLimitAdapterAPC(); // Use APC as Storage
// $adapter = new RateLimitAdapterRedis(); // Use Redis as Storage
// $adapter = new RateLimitAdapterPredis(new \Predis\Client()); // Use Predis as Storage

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
