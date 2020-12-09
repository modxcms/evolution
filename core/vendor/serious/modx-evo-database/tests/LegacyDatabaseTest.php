<?php namespace AgelxNash\Modx\Evo\Database\Tests;

use PHPUnit\Framework\TestCase;
use AgelxNash\Modx\Evo\Database;

class LegacyDatabaseTest extends TestCase
{
    /**
     * @var Database\LegacyDatabase
     */
    protected $instance;

    protected function setUp()
    {
        $this->instance = new Database\LegacyDatabase(
            isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'localhost',
            isset($_SERVER['DB_BASE']) ? $_SERVER['DB_BASE'] : 'modx',
          isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'homestead',
       isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : 'secret',
           isset($_SERVER['DB_PREFIX']) ? $_SERVER['DB_PREFIX'] : '{PREFIX}',
            isset($_SERVER['DB_CHARSET']) ? $_SERVER['DB_CHARSET'] : 'utf8mb4',
            isset($_SERVER['DB_METHOD']) ? $_SERVER['DB_METHOD'] : 'SET NAMES',
            isset($_SERVER['DB_COLLATION']) ? $_SERVER['DB_COLLATION'] : 'utf8mb4_unicode_ci',
            Database\Drivers\MySqliDriver::class
        );

        $this->instance->setDebug(true)->connect();
    }

    public function testPrefix()
    {
        $this->assertEquals(
            $this->instance->query("SELECT count(*) FROM [+prefix+]site_content"),
            $this->instance->query("SELECT count(*) FROM " . $this->instance->getFullTableName('site_content'))
        );

        $allQuery = $this->instance->getAllExecutedQuery();

        $this->assertEquals(
            array_pop($allQuery)['sql'],
            array_pop($allQuery)['sql']
        );
    }

    public function testReplaceFullTableName()
    {
        $this->assertEquals(
            'site_content',
            $this->instance->replaceFullTableName('site_content')
        );
        $this->assertEquals(
            $this->instance->getFullTableName('site_content'),
            $this->instance->replaceFullTableName('site_content', true)
        );

        $this->assertEquals(
            $this->instance->getFullTableName('site_content'),
            $this->instance->replaceFullTableName('[+prefix+]site_content')
        );

        $this->assertEquals(
            $this->instance->getFullTableName('[+prefix+]site_content'),
            $this->instance->replaceFullTableName('[+prefix+]site_content', true)
        );
    }

    public function testArrayQuery()
    {
        $queries = [
            "SELECT count(*) FROM [+prefix+]site_content",
            "SELECT count(*) FROM " . $this->instance->getFullTableName('site_content')
        ];

        $this->assertCount(
            2,
            $this->instance->query($queries)
        );
    }
}
