# Access Watch PHP library

A PHP library to log and analyse HTTP requests using the [Access Watch](http://access.watch/) cloud service.

Based on the more generic [Bouncer](https://github.com/znarf/bouncer) library.

## Installation

Install the latest version with

```bash
$ composer require access-watch/access-watch
```

## Basic Usage

Start Access Watch as soon as possible in your codebase.

You will need an API key for it.

To get an API key, send us an email at api@access.watch and we will come back to you.

```php
<?php

use \AccessWatch\AccessWatch;

$accessWatch = new AccessWatch(array(
  'apiKey' => 'ACCESS_WATCH_API_KEY_HERE',
));

$accessWatch->start();
```

## Cache

Out of the basic logging scenario, to properly operate, a cache backend needs to be defined. If no cache is set, the library will try to use APC/APCu.

Check the documentation of the [Bouncer](https://github.com/znarf/bouncer) library for more information about caching.

```php
<?php

use \AccessWatch\AccessWatch;

$memcache = new Memcache();
$memcache->addServer('localhost');

$accessWatch = new AccessWatch(array(
  'cache' => \Bouncer\Cache\Memcache($memcache)
));

$accessWatch->start();
```

### Author

François Hodierne - <francois@access.watch> - <http://access.watch/>

### License

The Access Watch PHP library is licensed under the MIT License - see the `LICENSE` file for details
