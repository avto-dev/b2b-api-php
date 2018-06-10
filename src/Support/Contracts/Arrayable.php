<?php

namespace AvtoDev\B2BApi\Support\Contracts;

interface Arrayable
{
    /**
     * Возвращает объект ответа в виде массива.
     *
     * @return array
     */
    public function toArray();
}
