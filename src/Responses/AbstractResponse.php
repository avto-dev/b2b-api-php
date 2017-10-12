<?php

namespace AvtoDev\B2BApi\Responses;

use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Traits\ConvertToArray;
use AvtoDev\B2BApi\Traits\ConvertToCarbon;
use AvtoDev\B2BApi\Traits\StackValuesDotAccessible;

/**
 * Class AbstractResponse.
 */
abstract class AbstractResponse implements ResponseInterface
{
    use ConvertToCarbon, ConvertToArray, StackValuesDotAccessible {
        getStackValueWithDot as protected;
        getStackValueWithDot as getValue;
    }

    /**
     * "Сырой" ответ от B2B, в виде массива. Либо null при его отсутствии.
     *
     * @var array|null
     */
    protected $raw;

    /**
     * AbstractResponse constructor.
     *
     * @param array|string|Response|object|null $input
     */
    public function __construct($input = null)
    {
        if (! empty($input) || is_object($input)) {
            $this->setRaw($input);
        }
    }

    /**
     * Возвращает "сырой" ответ от B2B.
     *
     * @return array|null
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Устанавливает "сырой" ответ от B2B, попутно пытаясь его преобразовать в массив.
     *
     * @param array|string|Response|object|null $value
     *
     * @return static|self
     */
    public function setRaw($value)
    {
        $this->raw = $this->convertToArray($value);

        return $this;
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
     *
     * Внимание! Данный метод можно смело переопределять в потомках.
     */
    public function toArray()
    {
        return (array) $this->raw;
    }

    /**
     * {@inheritdoc}
     *
     * @see StackValuesDotAccessible::getAccessorStack()
     */
    protected function getAccessorStack()
    {
        return $this->raw;
    }
}
