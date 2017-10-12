<?php

namespace AvtoDev\B2BApi\Tests\References\Mocks;

use AvtoDev\B2BApi\References\AbstractReference;

class ReferenceMock extends AbstractReference
{
    public static function getAll()
    {
        return [
            'aaa',
            'bbb',
        ];
    }
}
