Redis session handler through a locking mechanism
=================================================


Description
-----------
Used to store php sessions radishes.

The mechanism locks: one is the process of working with the session, the second process is waiting.


Installation
------------

```
composer require dmitry-suffi/redis-session-handler
```

Using
-----

```php


$redis = new Redis();
if ($redis->connect('11.111.111.11', 6379) && $redis->select(0)) {
    $handler = new \suffi\RedisSessionHandler\RedisSessionHandler($redis);
    session_set_save_handler($handler);
}

session_start();

```