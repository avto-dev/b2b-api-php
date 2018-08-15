<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\References\QueryTypes;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;
use AvtoDev\B2BApi\Exceptions\B2BApiInvalidArgumentException;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportStatusData;
use AvtoDev\B2BApi\Responses\DataTypes\ReportType\ReportTypeData;

class UserReportCommandsTest extends ClientTestCase
{
    /**
     * Тест вызова метода `report()->types()`.
     */
    public function testReportTypes()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->types($this->dev_token)
        );

        foreach (['state', 'size', 'total', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(2, $response->data()->count());
        $response->data()->each(function (ReportTypeData $item) {
            $this->assertInstanceOf(ReportTypeData::class, $item);
            foreach (['state', 'day_quote', 'month_quote', 'total_quote', 'content'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }

    /**
     * Тест вызова метода `report()->getAll()`.
     */
    public function testReportGetAll()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->getAll($this->dev_token)
        );

        foreach (['state', 'size', 'total', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(10, $response->data()->count());
        $response->data()->each(function (ReportData $item) {
            $this->assertInstanceOf(ReportData::class, $item);
            foreach (['uid', 'content', 'state'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }

    /**
     * Тест вызова метода `report()->get()`.
     */
    public function testReportGet()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->get($this->dev_token, $this->dev_test_report_uid)
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(1, $response->data()->count());
        $response->data()->each(function (ReportData $item) {
            $this->assertInstanceOf(ReportData::class, $item);
            foreach (['uid', 'content', 'state'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }

    /**
     * Тест вызова метода `report()->make()`.
     */
    public function testReportMake()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->make($this->dev_token, QueryTypes::QUERY_TYPE_GRZ, 'A111AA177', $this->dev_test_report_uid)
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(1, $response->data()->count());
        $response->data()->each(function (ReportStatusData $item) {
            $this->assertInstanceOf(ReportStatusData::class, $item);
            foreach (['uid', 'isnew', 'process_request_uid'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }

    /**
     * Тест вызова метода `report()->make()` с некорректным типом запроса.
     */
    public function testReportMakeWithInvalidQueryTypeValue()
    {
        $this->expectException(B2BApiInvalidArgumentException::class);

        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->make(
                $this->dev_token,
                'bla bla',
                'A111AA177',
                $this->dev_test_report_type_uid,
                true,
                []
            )
        );
    }

    /**
     * Тест вызова метода `report()->refresh()`.
     */
    public function testReportRefresh()
    {
        $this->assertInstanceOf(
            B2BResponse::class,
            $response = $this->client->user()->report()->refresh(
                $this->dev_token,
                $this->dev_test_report_uid,
                []
            )
        );

        foreach (['state', 'size', 'stamp', 'data'] as $key) {
            $this->assertArrayHasKey($key, $response->toArray());
        }

        $this->assertEquals(1, $response->data()->count());
        $response->data()->each(function (ReportStatusData $item) {
            $this->assertInstanceOf(ReportStatusData::class, $item);
            foreach (['uid', 'isnew', 'process_request_uid'] as $key) {
                $this->assertArrayHasKey($key, $item->toArray());
            }
        });
    }
}
