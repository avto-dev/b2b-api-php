<?php

namespace AvtoDev\B2BApi\Support\Contracts;

/**
 * Interface ResponseInterface.
 */
interface Arrayable
{
    /**
     * Возвращает объект ответа в виде массива.
     *
     * @return array
     */
    public function toArray();
}
