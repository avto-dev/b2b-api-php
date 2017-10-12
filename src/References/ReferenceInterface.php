<?php

namespace AvtoDev\B2BApi\References;

/**
 * Interface ReferenceInterface.
 */
interface ReferenceInterface
{
    /**
     * Возвращает массив всех возможных значений справочника.
     *
     * @return string[]|array
     */
    public static function getAll();

    /**
     * Проверяет - имеется ли переданное методу значение в справочнике.
     *
     * @param $value
     *
     * @return bool
     */
    public static function has($value);
}
