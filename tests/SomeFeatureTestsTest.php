<?php

namespace AvtoDev\B2BApi\Tests;

use Carbon\Carbon;
use AvtoDev\B2BApi\Tokens\AuthToken;
use AvtoDev\B2BApi\Clients\v1\Client;
use AvtoDev\B2BApi\References\QueryTypes;
use AvtoDev\B2BApi\Responses\DataTypes\User\BalanceData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;
use AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportStatusData;
use AvtoDev\B2BApi\Responses\DataTypes\ReportType\ReportTypeData;

/**
 * Class SomeFeatureTestsTest.
 *
 * Функциональные тесты.
 *
 * @group feature
 */
class SomeFeatureTestsTest extends AbstractUnitTestCase
{
    /**
     * Путь к env-файлу, значения из которого переопределяют те, что указаны в значениях "по умолчанию".
     *
     * @var string
     */
    const ENV_FILE_PATH = __DIR__ . '/env.php';

    /**
     * Значение, говорящее о том что тесты выполняются на реальных запросах, или же лишь их имитации.
     *
     * Внимание! Переключив данное значение в false будут осуществляться РЕАЛЬНЫЕ запросы к B2B API.
     *
     * @var bool
     */
    protected $is_test = true;

    /**
     * Инстанс клиента для работы с B2B API.
     *
     * @var Client
     */
    protected $client;

    /**
     * Имя пользователя B2B API.
     *
     * @var string
     */
    protected $username;

    /**
     * Пароль пользователя B2B API.
     *
     * @var string
     */
    protected $password;

    /**
     * Домен пользователя B2B API.
     *
     * @var string
     */
    protected $domain;

    /**
     * UID типа отчета B2B API.
     *
     * @var string
     */
    protected $report_type_uid;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $properties            = $this->getPropertiesForTests();
        $this->username        = $properties['client']['username'];
        $this->password        = $properties['client']['password'];
        $this->domain          = $properties['client']['domain'];
        $this->report_type_uid = $properties['client']['report_type_uid'];
        $this->is_test         = $properties['is_test'];

        $this->client = new Client($properties);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->client);

        unset($this->username);
        unset($this->password);
        unset($this->domain);
        unset($this->report_type_uid);

        parent::tearDown();
    }

    /**
     * Тест метода `->dev()->ping()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\Dev\DevCommandsGroup::ping()
     *
     * @return void
     */
    public function testDevPingMethod()
    {
        $response = $this->client->dev()->ping();

        $this->assertEquals(0, $response->data()->count());
        $this->assertEquals('pong', $response->getValue('value'));
    }

    /**
     * Тест метода `->dev()->token()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\Dev\DevCommandsGroup::token()
     *
     * @return void
     */
    public function testDevTokenMethod()
    {
        $age = 3600;
        $now = Carbon::now()->getTimestamp();

        $this->assertEquals(
            ! $this->is_test ? AuthToken::generate(
                $this->username,
                $this->password,
                null, // <-- Не указываем домен
                $age,
                $now
            ) : 'NOT:AVAILABLE:DURING:TESTING',
            $response = $this->client->dev()->token(
                $this->username,
                $this->password,
                false,
                $now,
                $age
            )->getValue('header')
        );

        $this->assertEquals(
            ! $this->is_test ? AuthToken::generate(
                $this->username,
                $this->password,
                $this->domain, // <-- Указываем домен
                $age,
                $now
            ) : 'NOT:AVAILABLE:DURING:TESTING',
            $response = $this->client->dev()->token(
                sprintf('%s@%s', $this->username, $this->domain),
                $this->password,
                false,
                $now,
                $age
            )->getValue('header')
        );
    }

    /**
     * Тест метода `->dev()->user()->reports()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\Dev\User\UserCommandsGroup::reports()
     *
     * @return void
     */
    public function testDevReportsMethod()
    {
        // На момент написания этих строк B2B работал только с данными харкодными значениями
        $response = $this->client->dev()->user()->reports(
            'AR-REST ZGVmYXVsdEB0ZXN0OjE0ODMyMjg4MDA6MTU3NjgwMDAwOnVjQk9kOGZhc3hIMkR3bVgrOHhhcVE9PQ==',
            'report_1_15523',
            true,
            true
        );

        $this->assertEquals(1, $response->data()->count());
        $this->assertInstanceOf(ReportData::class, $report = $response->data()->first());
        /* @var ReportData $report */
        $this->assertTrue($report->generationIsCompleted());
        $this->assertIsNotEmptyString($report->getField('identifiers.vehicle.vin'));
    }

    /**
     * Тест метода `->dev()->user()->info()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\UserCommandsGroup::info()
     *
     * @return void
     */
    public function testUserInfoMethod()
    {
        $response = $this->client->user()->info($this->getAuthToken());

        $this->assertTrue($response->data()->count() >= 1);
        $this->assertInstanceOf(UserInfoData::class, $user_info = $response->data()->first());
        /* @var UserInfoData $user_info */
        $this->assertEquals($this->domain, $user_info->getDomainUid());
        $this->assertEquals(sprintf('%s@%s', $this->username, $this->domain), $user_info->getLogin());
    }

    /**
     * Тест метода `->dev()->user()->balance()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\UserCommandsGroup::balance()
     *
     * @return void
     */
    public function testUserBalanceMethod()
    {
        $response = $this->client->user()->balance($this->getAuthToken(), $this->report_type_uid);

        $this->assertTrue($response->data()->count() >= 1);
        $this->assertInstanceOf(BalanceData::class, $balance_info = $response->data()->first());
        /* @var BalanceData $balance_info */
        $this->assertEquals(
            sprintf('%s@%s', $this->report_type_uid, $this->domain),
            $balance_info->getReportTypeUid()
        );
    }

    /**
     * Тест метода `->dev()->user()->report()->types()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup::types()
     *
     * @return void
     */
    public function testUserReportTypesMethod()
    {
        $response = $this->client->user()->report()->types($this->getAuthToken(), $this->report_type_uid);

        $this->assertTrue($response->data()->count() >= 1);
        $this->assertInstanceOf(ReportTypeData::class, $type = $response->data()->first());
        /* @var ReportTypeData $type */
        $this->assertContains($this->domain, $type->getUid());
        $this->assertInstanceOf(Carbon::class, $type->getCreatedAt());
        $this->assertNotEmpty($type->getSourcesNamesList());
        $this->assertEquals($this->domain, $type->getDomainUid());
    }

    /**
     * Тест метода `->dev()->user()->report()->getAll()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup::getAll()
     *
     * @return void
     */
    public function testUserReportGetAllMethod()
    {
        $response = $this->client->user()->report()->getAll($this->getAuthToken(), 200, 0, 1);

        $this->assertTrue($response->data()->count() >= 1);
        $this->assertInstanceOf(ReportData::class, $report = $response->data()->first());
        /* @var ReportData $report */
        $this->assertTrue($report->getTotalSourcesCount() >= 1);
        $this->assertContains($this->domain, $report->getReportTypeUid());
        $this->assertContains($this->domain, $report->getUid());
        $this->assertEquals($this->domain, $report->getDomainUid());
        $this->assertInstanceOf(Carbon::class, $report->getCreatedAt());
        $this->assertNotEmpty($report->getSourcesNames());
    }

    /**
     * Тест метода `->dev()->user()->report()->get()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup::get()
     *
     * @return void
     */
    public function testUserReportGetMethod()
    {
        // Получаем UID крайнего запрошенного отчета
        /** @var ReportData $report */
        $report = $this->client->user()->report()
            ->getAll($this->getAuthToken(), 1)
            ->data()->first();
        $this->assertInstanceOf(ReportData::class, $report);
        $report_uid = $report->getUid();

        $response = $this->client->user()->report()
            ->get($this->getAuthToken(), $report_uid, true, true);

        $this->assertCount(1, $response->data());
        $this->assertInstanceOf(ReportData::class, $report = $response->data()->first());
        /* @var ReportData $report */
        $this->assertEquals($report_uid, $report->getUid());
        $this->assertTrue($report->getTotalSourcesCount() >= 1);
        $this->assertContains($this->domain, $report->getReportTypeUid());
        $this->assertContains($this->domain, $report->getUid());
        $this->assertEquals($this->domain, $report->getDomainUid());
        $this->assertInstanceOf(Carbon::class, $report->getCreatedAt());
        $this->assertNotEmpty($report->getSourcesNames());
    }

    /**
     * Тест метода `->dev()->user()->report()->make()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup::make()
     *
     * @return void
     */
    public function testUserReportMakeMethod()
    {
        $response = $this->client->user()->report()->make(
            $this->getAuthToken(),
            QueryTypes::QUERY_TYPE_GRZ,
            'A111AA177',
            $this->report_type_uid
        );

        $this->assertTrue($response->isSuccess());
        $this->assertCount(1, $response->data());
        $this->assertInstanceOf(ReportStatusData::class, $status = $response->data()->first());
        /* @var ReportStatusData $status */
        $this->assertInstanceOf(Carbon::class, $status->getSuggestGet());
        $this->assertIsNotEmptyString($status->getUid());
    }

    /**
     * Тест метода `->dev()->user()->report()->refresh()`.
     *
     * @see \AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup::refresh()
     *
     * @return void
     */
    public function testUserReportRefreshMethod()
    {
        // Получаем UID крайнего запрошенного отчета
        /** @var ReportData $report */
        $report = $this->client->user()->report()
            ->getAll($this->getAuthToken(), 1)
            ->data()->first();
        $this->assertInstanceOf(ReportData::class, $report);
        $report_uid = $report->getUid();

        $response = $this->client->user()->report()
            ->refresh($this->getAuthToken(), $report_uid);

        $this->assertTrue($response->isSuccess());
        $this->assertCount(1, $response->data());
        $this->assertInstanceOf(ReportStatusData::class, $status = $response->data()->first());
        /* @var ReportStatusData $status */
        $this->assertInstanceOf(Carbon::class, $status->getSuggestGet());
        $this->assertIsNotEmptyString($uid = $status->getUid());
        $this->assertTrue($status->isNew());
        $this->assertEquals($uid, $status->getProcessRequestUid());
    }

    /**
     * Возвращает токен авторизации для сервиса B2B API.
     *
     * @return string
     */
    protected function getAuthToken()
    {
        return AuthToken::generate($this->username, $this->password, $this->domain);
    }

    /**
     * Возвращает массив с предопределенными значениями, с которыми должны выполняться данные тесты.
     *
     * @return array
     */
    protected function getPropertiesForTests()
    {
        static $env = [];

        if (empty($env)) {
            if ((static::ENV_FILE_PATH) && file_exists(static::ENV_FILE_PATH)) {
                $env = (array) require static::ENV_FILE_PATH;
            }
        }

        return array_replace_recursive([
            'api' => [
                'versions' => [
                    'v1' => [
                        'base_uri' => 'http://some.fake.uri/api',
                    ],
                ],
            ],

            'use_api_version' => 'v1',

            'client' => [
                'username'        => 'username',
                'password'        => 'password',
                'domain'          => 'domain',
                'report_type_uid' => 'report_type_uid',
            ],

            'is_test' => true,
        ], $env);
    }
}
