<?php

namespace AvtoDev\B2BApi\References;

use AvtoDev\B2BApi\Support\Contracts\Jsonable;
use AvtoDev\B2BApi\Support\Contracts\Arrayable;

/**
 * Абстрактный класс справочника. Как правило, справочник реализует статические методы для получения данных.
 */
abstract class AbstractReference implements ReferenceInterface, Arrayable, Jsonable
{
    /**
     * {@inheritdoc}
     */
    public static function has($value)
    {
        return \in_array($value, static::getAll(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return (array) static::getAll();
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return \json_encode($this->toArray(), $options);
    }
}
