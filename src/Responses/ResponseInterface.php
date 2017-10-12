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
     * Возвращает значение из стека данных, обращаясь к нему с помощью dot-нотации.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getValue($path, $default = null);
}
