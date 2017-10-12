<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Exceptions\B2BApiException;

class DevCommandsTest extends ClientTestCase
{
    /**
     * Тест метода `ping()`.
     *
     * @return void
     */
    public function testPing()
    {
        $response = $this->client->dev()->ping();

        $this->assertInstanceOf(B2BResponse::class, $response);
        $this->assertEquals('pong', $response->getValue('value'));

        foreach (['value', 'in', 'out', 'delay'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }
    }

    /**
     * Тест вызова метода `token()`.
     */
    public function testToken()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->dev()->token($this->dev_username, $this->dev_password)
        );

        foreach (['user', 'pass', 'pass_hash', 'date', 'stamp', 'age', 'salt', 'salted_pass_hash', 'token'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }
    }

    /**
     * Тест вызова метода `token()` с некорректной датой.
     */
    public function testTokenWithInvalidDate()
    {
        $this->expectException(B2BApiException::class);

        $this->client->dev()->token($this->dev_username, $this->dev_password, false, new \stdClass);
    }

    /**
     * Тест вызова метода `token()` с некорректным параметром "age".
     */
    public function testTokenWithInvalidAge()
    {
        $this->expectException(B2BApiException::class);

        $this->client->dev()->token($this->dev_username, $this->dev_password, false, null, -1);
    }

    /**
     * Тест вызова метода `token()` с пустым именем пользователя.
     */
    public function testTokenWithEmptyUsername()
    {
        $this->expectException(B2BApiException::class);

        $this->client->dev()->token('', $this->dev_password);
    }

    /**
     * Тест вызова метода `token()` с пустым паролем пользователя.
     */
    public function testTokenWithEmptyPassword()
    {
        $this->expectException(B2BApiException::class);

        $this->client->dev()->token($this->dev_username, null);
    }
}
