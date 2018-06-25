<?php namespace AgelxNash\Modx\Evo\Database\Tests\Traits\Source;

use PHPUnit\Framework\TestCase;
use AgelxNash\Modx\Evo\Database;

class DebugTraitTest extends TestCase
{
    protected $instance;

    public function setUp()
    {
        $this->instance = new Database\Database([]);
    }


    public function testDebug()
    {
        $this->assertFalse(
            $this->instance->isDebug()
        );

        $this->instance->setDebug(true);

        $this->assertTrue(
            $this->instance->isDebug()
        );
    }


    public function testGetAllExecutedQuery()
    {
        $this->assertSame(
            [],
            $this->instance->getAllExecutedQuery()
        );
    }

    public function testGetLastQuery()
    {
        $this->assertSame(
            '',
            $this->instance->getLastQuery()
        );
    }

    public function testRenderConnectionTime()
    {
        $this->assertThat(
            $this->instance->renderConnectionTime(),
            $this->isType('string')
        );
    }

    public function testRenderExecutedQuery()
    {
        $this->assertThat(
            $this->instance->renderExecutedQuery(),
            $this->isType('string')
        );
    }

    public function testErrorList()
    {
        $this->assertTrue(
            $this->instance->flushIgnoreErrors()
        );

        $this->assertEquals(
            [],
            $this->instance->getIgnoreErrors()
        );

        $error = '42S22';
        $this->assertEquals(
            $error,
            $this->instance->addIgnoreErrors($error)
        );

        $this->assertEquals(
            [$error],
            $this->instance->getIgnoreErrors()
        );

        $errors = ['42000', '42S22'];
        $this->assertEquals(
            $errors,
            $this->instance->setIgnoreErrors($errors)
        );

        $this->assertEquals(
            $errors,
            $this->instance->getIgnoreErrors()
        );
    }
}
