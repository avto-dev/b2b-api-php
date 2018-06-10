<?php

namespace AvtoDev\B2BApi\Tests\Exceptions;

use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Exceptions\B2BApiInvalidArgumentException;

/**
 * Тест исключения неверного токена.
 */
class B2BApiInvalidArgumentExceptionTest extends AbstractUnitTestCase
{
    /**
     * Просто чекаем родословную.
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(B2BApiException::class, new B2BApiInvalidArgumentException);
    }
}
