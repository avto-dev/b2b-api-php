<?php

namespace AvtoDev\B2BApi\Tests;

use AvtoDev\B2BApi\Tests\References\Mocks\ReferenceMock;

class AbstractReferenceTest extends AbstractUnitTestCase
{
    /**
     * @var ReferenceMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new ReferenceMock;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест метода `has()`.
     */
    public function testHas()
    {
        $this->assertTrue($this->instance->has('aaa'));
        $this->assertFalse($this->instance->has('zzz'));
    }

    /**
     * Тест методов `toArray()` и `toJson()`.
     *
     * @return void
     */
    public function testToArrayAndToJson()
    {
        $this->assertIsArray($this->instance->toArray());
        $this->assertJson($this->instance->toJson());
    }
}
