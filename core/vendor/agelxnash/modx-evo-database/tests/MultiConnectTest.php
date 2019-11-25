<?php namespace AgelxNash\Modx\Evo\Database\Tests;

use PHPUnit\Framework\TestCase;
use AgelxNash\Modx\Evo\Database;
use Illuminate\Database\Capsule\Manager as Capsule;

class MultiConnectTest extends TestCase
{
    /**
     * @var array
     */
    protected $config = [];

    protected function setUp()
    {
        $this->config = [
            'host' => isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'localhost',
            'database' => isset($_SERVER['DB_BASE']) ? $_SERVER['DB_BASE'] : 'modx',
            'username' => isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'homestead',
            'password' => isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : 'secret',
            'prefix' => isset($_SERVER['DB_PREFIX']) ? $_SERVER['DB_PREFIX'] : '{PREFIX}',
            'charset' => isset($_SERVER['DB_CHARSET']) ? $_SERVER['DB_CHARSET'] : 'utf8mb4',
            'method' => isset($_SERVER['DB_METHOD']) ? $_SERVER['DB_METHOD'] : 'SET NAMES',
            'collation' => isset($_SERVER['DB_COLLATION']) ? $_SERVER['DB_COLLATION'] : 'utf8mb4_unicode_ci',
        ];
    }

    public function testPredefinedMySqliDriver()
    {
        $driver = new Database\Drivers\MySqliDriver($this->config);
        $instance = new Database\Database([], $driver);

        $this->assertThat(
            $instance->getVersion(),
            $this->isType('string')
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig()
        );
    }

    public function testPredefinedIlluminateDriver()
    {
        $driver = new Database\Drivers\IlluminateDriver($this->config);
        $instance = new Database\Database([], $driver);

        $this->assertThat(
            $instance->getVersion(),
            $this->isType('string')
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig()
        );
    }

    public function testPresetIlluminateConnection()
    {
        $capsule = new Capsule;
        $capsule->setAsGlobal();
        $capsule->addConnection(['driver' => 'mysql'] + $this->config);
        $capsule->getConnection();

        $driver = new Database\Drivers\IlluminateDriver();
        $instance = new Database\Database([], $driver);

        $this->assertThat(
            $instance->getVersion(),
            $this->isType('string')
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig()
        );
    }

    public function testPresetNoDefaultIlluminateConnection()
    {
        $name = 'modx';

        $capsule = new Capsule;
        $capsule->setAsGlobal();
        $capsule->addConnection(['driver' => 'mysql'] + $this->config, $name);
        $capsule->getConnection($name);

        $driver = new Database\Drivers\IlluminateDriver([], $name);
        $instance = new Database\Database([], $driver);

        $this->assertThat(
            $instance->getVersion(),
            $this->isType('string')
        );

        $this->assertEquals(
            $this->config,
            $instance->getConfig()
        );
    }

    public function testDuplicateIlluminateConnection()
    {
        try {
            $capsule = new Capsule;
            $capsule->setAsGlobal();
            $capsule->addConnection(['driver' => 'mysql'] + $this->config);
            $capsule->getConnection();

            $driver = new Database\Drivers\IlluminateDriver(array_merge($this->config, ['host' => 'agel-nash.ru']));
            new Database\Database([], $driver);

            $this->assertTrue(false, 'Need Database\Exceptions\ConnectException');
        } catch (Database\Exceptions\ConnectException $exception) {
            $this->assertInstanceOf(
                Database\Exceptions\ConnectException::class,
                $exception
            );
        }
    }
}
