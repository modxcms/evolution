<?php namespace AgelxNash\Modx\Evo\Database\Tests\Real;

use AgelxNash\Modx\Evo\Database\Tests\RealQueryTest;
use PDOStatement;
use AgelxNash\Modx\Evo\Database\Drivers\IlluminateDriver;
use Illuminate;

/**
 * @property IlluminateDriver $instance
 */
class IlluminateQueryTest extends RealQueryTest
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

    public function testBothGetRow()
    {
        $query = 'SELECT `id`, `alias` FROM ' . $this->table . ' WHERE id = 1';
        $result = $this->instance->query($query);

        $this->assertEquals(
            [0 => '1', 'id' => '1', 1 => 'index', 'alias' => 'index'],
            $this->instance->getRow($result, 'both')
        );
    }


    public function testObjecGetRow()
    {
        $query = 'SELECT `id`, `alias` FROM ' . $this->table . ' WHERE id = 1';
        $result = $this->instance->query($query);

        $this->assertEquals(
            (object)['id' => '1', 'alias' => 'index'],
            $this->instance->getRow($result, 'object')
        );
    }
}
