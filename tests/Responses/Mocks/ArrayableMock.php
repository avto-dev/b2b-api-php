<?php

namespace AvtoDev\B2BApi\Tests\Responses\Mocks;

class ArrayableMock
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toArray()
    {
        return (array) $this->value;
    }
}
