<?php

namespace AvtoDev\B2BApi\Tests\Exceptions;

use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Exceptions\B2BApiInvalidTokenException;

/**
 * Class B2BApiInvalidTokenExceptionTest.
 *
 * Тест исключения неверного токена.
 */
class B2BApiInvalidTokenExceptionTest extends AbstractUnitTestCase
{
    /**
     * Просто чекаем родословную.
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(B2BApiException::class, new B2BApiInvalidTokenException);
    }
}
