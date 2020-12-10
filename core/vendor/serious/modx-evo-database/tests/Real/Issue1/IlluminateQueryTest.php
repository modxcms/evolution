<?php namespace AgelxNash\Modx\Evo\Database\Tests\Real\Issue1;

use PDOStatement;
use AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver;
use Illuminate;

class IlluminateQueryTest extends AbstractIssue1Case
{
    protected $driver = IlluminateDriver::class;
    protected $connectorClass = Illuminate\Database\ConnectionInterface::class;
    protected $resultClass = PDOStatement::class;

    protected function setUp()
    {
        if (!class_exists(Illuminate\Database\Connection::class)) {
            $this->markTestSkipped(
                'The Illuminate\Database\Connection class is not available.'
            );
        }

        parent::setUp();
    }
}
