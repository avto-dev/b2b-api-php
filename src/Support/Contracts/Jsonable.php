<?php

namespace AvtoDev\B2BApi\Support\Contracts;

/**
 * Interface Jsonable.
 */
interface Jsonable
{
    /**
     * Возвращает объект ответа в виде json-строки.
     *
     * @param int $options
     *
     * @return mixed
     */
    public function toJson($options = 0);
}
