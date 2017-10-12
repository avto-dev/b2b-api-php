<?php

namespace AvtoDev\B2BApi\Support\Contracts;

/**
 * Interface Configurable.
 */
interface Configurable
{
    /**
     * Метод конфигурации объекта.
     *
     * @param array|string|object|null $content
     *
     * @return void
     */
    public function configure($content);
}
