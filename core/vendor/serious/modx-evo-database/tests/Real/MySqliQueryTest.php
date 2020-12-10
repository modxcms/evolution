<?php namespace AgelxNash\Modx\Evo\Database\Tests\Real;

use AgelxNash\Modx\Evo\Database\Tests\RealQueryTest;
use mysqli;
use mysqli_result;
use AgelxNash\Modx\Evo\Database\Drivers\MySqliDriver;

/**
 * @property MySqliDriver $instance
 */
class MySqliQueryTest extends RealQueryTest
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

    public function testDataSeek()
    {
        $query = 'SELECT `id`, `alias` FROM ' . $this->table . ' WHERE id = 1';

        $result = $this->instance->query($query);
        $this->assertInstanceOf(
            $this->resultClass,
            $result
        );

        $data = $this->instance->getRow($query);

        $this->assertEquals(
            ['id' => 1, 'alias' => 'index'],
            $data
        );

        $this->assertEquals(
            $data,
            $this->instance->getRow($result)
        );

        $this->assertTrue(
            $this->instance->getDriver()->dataSeek($result, 0)
        );

        $this->assertEquals(
            $data,
            $this->instance->getRow($result, 'assoc')
        );
        $this->instance->getDriver()->dataSeek($result, 0);

        $this->assertEquals(
            [0 => 1, 1 => 'index'],
            $this->instance->getRow($result, 'num')
        );
        $this->instance->getDriver()->dataSeek($result, 0);

        $this->assertEquals(
            (object)['id' => 1, 'alias' => 'index'],
            $this->instance->getRow($result, 'object')
        );
        $this->instance->getDriver()->dataSeek($result, 0);

        $this->assertEquals(
            [0 => '1', 'id' => '1', 1 => 'index', 'alias' => 'index'],
            $this->instance->getRow($result, 'both')
        );
    }
}
