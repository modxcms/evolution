<?php namespace AgelxNash\Modx\Evo\Database\Tests;

use AgelxNash\Modx\Evo\Database;
use PHPUnit\Framework\TestCase;

abstract class RealQueryCase extends TestCase
{
    /**
     * @var Database\Database
     */
    protected $instance;

    protected $driver;

    protected $connectorClass = '';

    protected $resultClass = '';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $db1;

    /**
     * @var string
     */
    protected $db2;

    protected function setUp()
    {
        $this->db1 = isset($_SERVER['DB_BASE']) ? $_SERVER['DB_BASE'] : 'modx';
        $this->db2 = isset($_SERVER['DB_BASE2']) ? $_SERVER['DB_BASE2'] : 'laravel';

        $this->config = [
            'host' => isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'localhost',
            'database' => $this->db1,
            'username' => isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'homestead',
            'password' => isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : 'secret',
            'prefix' => isset($_SERVER['DB_PREFIX']) ? $_SERVER['DB_PREFIX'] : '{PREFIX}',
            'charset' => isset($_SERVER['DB_CHARSET']) ? $_SERVER['DB_CHARSET'] : 'utf8mb4',
            'method' => isset($_SERVER['DB_METHOD']) ? $_SERVER['DB_METHOD'] : 'SET NAMES',
            'collation' => isset($_SERVER['DB_COLLATION']) ? $_SERVER['DB_COLLATION'] : 'utf8mb4_unicode_ci',
        ];

        $this->instance = new Database\Database($this->config, $this->driver);

        $this->instance->setDebug(true)->connect();

        $this->table = $this->instance->getFullTableName('site_content');
    }
}
