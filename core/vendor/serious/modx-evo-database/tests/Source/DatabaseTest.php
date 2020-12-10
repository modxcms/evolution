<?php namespace AgelxNash\Modx\Evo\Database\Tests\Source;

use PHPUnit\Framework\TestCase;
use AgelxNash\Modx\Evo\Database;

class DatabaseTest extends TestCase
{
    /**
     * @var Database\Database
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new Database\Database([]);
    }

    public function testDriver()
    {
        try {
            new Database\Database([], \stdClass::class);
            $this->assertTrue(false, 'Need DriverException');
        } catch (Database\Exceptions\DriverException $exception) {
            $this->assertInstanceOf(
                Database\Exceptions\DriverException::class,
                $exception
            );
        }
    }

    public function testConnection()
    {
        try {
            (new Database\Database(
                ['host' => 'agel-nash.ru', 'username' => 'homestead', 'password' => 'secret', 'database' => 'modx'])
            )->connect();
            $this->assertTrue(false, 'Need ConnectException');
        } catch (Database\Exceptions\ConnectException $exception) {
            $this->assertInstanceOf(
                Database\Exceptions\ConnectException::class,
                $exception
            );
        }
    }

    public function testIsResult()
    {
        $this->assertFalse($this->instance->isResult(null));
    }

    public function testConfig()
    {
        $config = [
            'host' => '',
            'database' => '',
            'username' => '',
            'password' => '',
            'prefix' => '',
            'charset' => 'utf8mb4',
            'method' => 'SET CHARACTER SET',
            'collation' => 'utf8mb4_unicode_ci'
        ];

        $this->assertSame(
            $config,
            (new Database\LegacyDatabase())->getConfig(),
            'STEP 1/8'
        );

        $this->assertSame(
            $config,
            (new Database\Database($config))->getConfig(),
            'STEP 2/8'
        );

        $this->assertSame(
            'agel-nash.ru',
            (new Database\Database(['host' => 'agel-nash.ru']))->getConfig('host'),
            'STEP 3/8'
        );

        $this->assertEquals(
            null,
            (new Database\Database([]))->getConfig('password'),
            'STEP 4/8'
        );

        $this->assertSame(
            'secret',
            (new Database\Database(['password' => 'secret']))->getConfig('password'),
            'STEP 5/8'
        );

        $this->assertSame(
            'modx',
            (new Database\Database(['database' => 'modx']))->getConfig('database'),
            'STEP 6/8'
        );

        $this->assertSame(
            'utf8mb4',
            (new Database\Database(['charset' => 'utf8mb4']))->getConfig('charset'),
            'STEP 7/8'
        );

        $this->assertSame(
            'utf8mb4_unicode_ci',
            (new Database\Database(['collation' => 'utf8mb4_unicode_ci']))->getConfig('collation'),
            'STEP 8/8'
        );
    }

    public function testInsertFailFields()
    {
        try {
            $this->instance->insert('0', 'site_content');
            $this->assertTrue(false, 'Need InvalidFieldException');
        } catch (Database\Exceptions\InvalidFieldException $exception) {
            $this->assertEquals(
                '0',
                $exception->getData()
            );
        }
    }
}
