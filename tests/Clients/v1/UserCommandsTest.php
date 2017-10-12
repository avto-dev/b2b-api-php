<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Responses\DataTypes\User\BalanceData;
use AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData;

class UserCommandsTest extends ClientTestCase
{
    /**
     * Тест вызова метода `info()`.
     */
    public function testInfo()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->info($this->dev_token)
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(1, $response->data()->count());
        $response->data()->each(function (UserInfoData $item) {
            $this->assertInstanceOf(UserInfoData::class, $item);
            foreach (['login', 'email', 'contacts', 'state', 'domain_uid', 'roles', 'uid', 'name', 'comment',
                         'tags', 'created_at', 'created_by', 'updated_at', 'updated_by', 'active_from',
                         'active_to', ] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }

    /**
     * Тест вызова метода `balance()`.
     */
    public function testBalance()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->balance($this->dev_token, $this->dev_test_report_uid)
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(1, $response->data()->count());
        $response->data()->each(function (BalanceData $item) {
            $this->assertInstanceOf(BalanceData::class, $item);
            foreach (['report_type_uid', 'balance_type', 'quote_init', 'quote_up', 'quote_use'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }
}
