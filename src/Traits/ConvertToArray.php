<?php

namespace AvtoDev\B2BApi\Traits;

use GuzzleHttp\Psr7\Response;

/**
 * Trait ConvertToArray.
 */
trait ConvertToArray
{
    /**
     * Преобразует прилетевший в метод объект - в массив. В противном случае вернёт null.
     *
     * @param array|string|Response|object|null $value
     *
     * @return array|null
     */
    protected function convertToArray($value)
    {
        // Если получен массив - то просто кидаем его в raw
        if (\is_array($value)) {
            return $value;
        }

        if (\is_string($value)) {
            // Если влетела строка - то считаем что это json, и пытаемся его разобрать
            $json = json_decode($value, true);
            if (\is_array($json) && \json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
        } elseif (\is_object($value) && \method_exists($value, 'toArray')) {
            // Если прилетел объект, и у него есть метод 'convertToArray' - то его и используем
            return $value->toArray();
        } elseif ($value instanceof Response) {
            // Немного рекурсии не помешает ;)
            return $this->convertToArray($value->getBody()->getContents());
        }
    }
}
