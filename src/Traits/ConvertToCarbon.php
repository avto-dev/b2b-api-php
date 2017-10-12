<?php

namespace AvtoDev\B2BApi\Traits;

use DateTime;
use Carbon\Carbon;

/**
 * Trait ConvertToCarbon.
 *
 * Трейт, реализующий метод конвертации в Carbon-объект.
 */
trait ConvertToCarbon
{
    /**
     * Преобразует полученное значение в объект Carbon.
     *
     * @param Carbon|DateTime|int|string $value
     *
     * @return null|Carbon
     */
    protected function convertToCarbon($value)
    {
        if ($value instanceof Carbon) {
            return $value;
        } elseif ($value instanceof DateTime) {
            return Carbon::instance($value);
        } elseif (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        } elseif (is_string($value)) {
            return Carbon::parse($value);
        }
    }
}
