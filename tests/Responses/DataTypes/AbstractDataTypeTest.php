<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use AvtoDev\B2BApi\Tests\Responses\DataTypes\Mocks\AbstractDataTypeMock;

class AbstractDataTypeTest extends AbstractDataTypeTestCase
{
    /**
     * @var AbstractDataTypeMock
     */
    protected $instance;

    /**
     * Имя класса, инстанс которого необходимо создавать в методе `setUp()`.
     *
     * @var string
     */
    protected $instance_class = AbstractDataTypeMock::class;

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

    /**
     * {@inheritdoc}
     */
    protected function getInstanceContent()
    {
        return [];
    }
}
