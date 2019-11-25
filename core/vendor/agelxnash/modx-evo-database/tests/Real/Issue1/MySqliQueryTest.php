<?php namespace AgelxNash\Modx\Evo\Database\Tests\Real\Issue1;

use mysqli;
use mysqli_result;
use AgelxNash\Modx\Evo\Database\Drivers\MySqliDriver;

class MySqliQueryTest extends AbstractIssue1Case
{
    protected $driver = MySqliDriver::class;
    protected $connectorClass = mysqli::class;

    protected $resultClass = mysqli_result::class;

    protected function setUp()
    {
        if (!class_exists(mysqli::class)) {
            $this->markTestSkipped(
                'The mysqli class is not available.'
            );
        }

        parent::setUp();
    }
}
