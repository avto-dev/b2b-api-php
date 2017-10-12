<?php

namespace AvtoDev\B2BApi\Responses\DataTypes;

use AvtoDev\B2BApi\Support\Contracts\Jsonable;
use AvtoDev\B2BApi\Support\Contracts\Arrayable;

interface DataTypeInterface extends Arrayable, Jsonable
{
    /**
     * AbstractDataType constructor.
     *
     * @param array|string|object|null $content
     */
    public function __construct($content = null);

    /**
     * Возвращает значение из тела данных, обращаясь к нему с помощью dot-нотации.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getContentValue($path, $default = null);
}
