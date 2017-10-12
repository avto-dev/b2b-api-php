<?php

namespace AvtoDev\B2BApi\Tests\Exceptions;

use Exception;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;

/**
 * Class B2BApiExceptionTest.
 *
 * Тест базового исключения.
 */
class B2BApiExceptionTest extends AbstractUnitTestCase
{
    /**
     * Просто чекаем родословную.
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(Exception::class, new B2BApiException);
    }
}
