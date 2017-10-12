<?php

namespace AvtoDev\B2BApi\Responses;

use AvtoDev\B2BApi\Support\Contracts\Jsonable;
use AvtoDev\B2BApi\Support\Contracts\Arrayable;

/**
 * Interface ResponseInterface.
 */
interface ResponseInterface extends Arrayable, Jsonable
{
    /**
     * Имя флага, по которому в ответ добавляется значение со временем исполнения запроса.
     */
    const REQUEST_DURATION_KEY_NAME = '__request_duration';

    /**
     * Возвращает значение из стека данных, обращаясь к нему с помощью dot-нотации.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getValue($path, $default = null);
}
