<?php

namespace AvtoDev\B2BApi\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractUnitTestCase extends TestCase
{
    /**
     * Проверяет, что элемент является массивом.
     *
     * @param $value
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function assertIsArray($value)
    {
        $this->assertTrue(\is_array($value), 'Must be an array');
    }

    /**
     * Проверяет, что элемент является не пустой строкой.
     *
     * @param $value
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function assertIsNotEmptyString($value)
    {
        $this->assertIsString($value);
        $this->assertNotEmpty($value);
    }

    /**
     * Проверяет, что элемент является строкой.
     *
     * @param $value
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function assertIsString($value)
    {
        $this->assertTrue(is_string($value), 'Must be string');
    }
}
