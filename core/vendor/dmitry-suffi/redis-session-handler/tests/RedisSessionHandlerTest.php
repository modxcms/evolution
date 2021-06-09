<?php

use \suffi\RedisSessionHandler\RedisSessionHandler;

class RedisSessionHandlerTest extends \PHPUnit\Framework\TestCase
{

    public function getRedis()
    {
        $savePath = ini_get('session.save_path');
        $redis = new Redis();
        $redis->pconnect($savePath);
        $redis->select(963);
        return $redis;
    }

    public function testReadWrite()
    {
        $handler = new RedisSessionHandler($this->getRedis());

        $sessionId = 'ses' . uniqid();
        $handler->write($sessionId, 'session data');

        $this->assertEquals($handler->read($sessionId), 'session data');
    }

    public function testLock()
    {
        $handler1 = new RedisSessionHandler($this->getRedis());
        $handler2 = new RedisSessionHandler($this->getRedis());

        $sessionId = 'ses' . uniqid();
        $handler1->write($sessionId, 'session data');
        $this->assertEquals($handler1->read($sessionId), 'session data');

        $handler2->setLockMaxWait(5);
        $this->assertNotEquals($handler2->read($sessionId), 'session data');
        $this->assertFalse($handler2->read($sessionId));

        $handler1->close();
        $this->assertEquals($handler2->read($sessionId), 'session data');
    }

}
