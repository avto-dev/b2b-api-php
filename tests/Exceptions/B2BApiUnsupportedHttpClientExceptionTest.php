<?php

namespace AvtoDev\B2BApi\Tests\Exceptions;

use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Exceptions\B2BApiUnsupportedHttpClientException;

/**
 * Class B2BApiUnsupportedHttpClientExceptionTest.
 *
 * Тест исключения неподдерживаемого http-клиент.
 */
class B2BApiUnsupportedHttpClientExceptionTest extends AbstractUnitTestCase
{
    /**
     * Просто чекаем родословную.
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(B2BApiException::class, new B2BApiUnsupportedHttpClientException);
    }
}
