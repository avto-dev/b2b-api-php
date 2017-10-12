<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Exceptions\B2BApiException;

class DevUserCommandsTest extends ClientTestCase
{
    /**
     * Тест вызова метода `token()`.
     */
    public function testToken()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->dev()->user()->reports($this->dev_token, $this->dev_test_report_uid)
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }
    }

    /**
     * Тест вызова метода `refresh()`.
     */
    public function testRefresh()
    {
        // Так как на момент написания этих строк B2B отдавал ошибку во время вызова данного метода - написать
        // корректный тест не представляется возможным
        $this->expectException(B2BApiException::class);

        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->dev()->user()->refresh($this->dev_token, $this->dev_test_report_uid)
        );
    }
}
