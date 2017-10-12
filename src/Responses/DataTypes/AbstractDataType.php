<?php

namespace AvtoDev\B2BApi\Responses\DataTypes;

use AvtoDev\B2BApi\Traits\ConvertToArray;
use AvtoDev\B2BApi\Traits\ConvertToCarbon;
use AvtoDev\B2BApi\Traits\StackValuesDotAccessible;

/**
 * Class AbstractDataType.
 */
abstract class AbstractDataType implements DataTypeInterface
{
    use ConvertToArray, ConvertToCarbon, StackValuesDotAccessible {
        getStackValueWithDot as protected;
        getStackValueWithDot as getContentValue;
    }

    /**
     * Контент объекта данных.
     *
     * @var array|null
     */
    protected $content;

    /**
     * AbstractDataType constructor.
     *
     * @param array|string|object|null $content
     */
    public function __construct($content = null)
    {
        $this->content = $this->convertToArray($content);
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return (array) $this->content;
    }

    /**
     * {@inheritdoc}
     *
     * @see StackValuesDotAccessible::getAccessorStack()
     */
    protected function getAccessorStack()
    {
        return $this->content;
    }
}
