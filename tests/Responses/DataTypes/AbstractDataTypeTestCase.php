<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Tests\Responses\DataTypes\Mocks\AbstractDataTypeMock;

abstract class AbstractDataTypeTestCase extends AbstractUnitTestCase
{
    /**
     * ВНИМАНИЕ! Данный phpdoc необходимо переопределять в потомках.
     *
     * @var AbstractDataTypeMock
     */
    protected $instance;

    /**
     * Имя класса, инстанс которого необходимо создавать в методе `setUp()`.
     *
     * ВНИМАНИЕ! Данный метод необходимо переопределять в потомках.
     *
     * @var string
     */
    protected $instance_class = AbstractDataTypeMock::class;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new $this->instance_class($this->getInstanceContent());
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
     * Возвращает контент данных, которые должны быть переданы в конструктор тестируемого инстанса.
     *
     * @return mixed
     */
    abstract protected function getInstanceContent();
}
