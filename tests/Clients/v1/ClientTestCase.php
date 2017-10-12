<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\Clients\v1\Client;
use AvtoDev\B2BApi\Tests\Clients\AbstractClientTestCase;

/**
 * Class ClientTestCase.
 */
abstract class ClientTestCase extends AbstractClientTestCase
{
    // Идентификатор пользователя
    protected $dev_username = 'test@test';

    // Пароль
    protected $dev_password = '123';

    // Токен авторизации
    protected $dev_token = 'AR-REST ZGVmYXVsdEB0ZXN0OjE0ODMyMjg4MDA6MTU3NjgwMDAwOnVjQk9kOGZhc3hIMkR3bVgrOHhhcVE9PQ==';

    // UID тестового отчета
    protected $dev_test_report_uid = 'report_1_15523';

    // UID тестового типа отчета
    protected $dev_test_report_type_uid = 'default';

    // Тип запрашиваемой сущности
    protected $dev_query_type = 'VIN';

    // Значение запрашиваемой сущности
    protected $dev_query_id = 'Z94CB41AAGR323020';

    /**
     * @var Client
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = new Client(['is_test' => true]);
    }
}
