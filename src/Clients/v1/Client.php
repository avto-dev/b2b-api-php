<?php

namespace AvtoDev\B2BApi\Clients\v1;

use AvtoDev\B2BApi\Clients\AbstractClient;
use AvtoDev\B2BApi\Clients\v1\Dev\DevCommandsGroup;
use AvtoDev\B2BApi\Clients\v1\User\UserCommandsGroup;

/**
 * Class Client.
 *
 * Клиент для работы с B2B API.
 */
class Client extends AbstractClient
{
    /**
     * API команды группы DEV.
     *
     * @var DevCommandsGroup
     */
    protected $dev;

    /**
     * API команды группы USER.
     *
     * @var UserCommandsGroup
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->dev  = new DevCommandsGroup($this);
        $this->user = new UserCommandsGroup($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [
            /*
             * Настройки API поставщика
             */
            'api'             => [
                /*
                 * Поддерживаемые версии API
                 */
                'versions' => [
                    /*
                     * Версия '1.0'
                     */
                    'v1' => [
                        // Базовый URI до API. Например: 'http://some.fake.uri/api'
                        'base_uri' => null,
                    ],
                ],
            ],

            // Используемая версия API поставщика
            'use_api_version' => 'v1',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getClientVersion()
    {
        return 'v2.0';
    }

    /**
     * API команды группы DEV.
     *
     * @return DevCommandsGroup
     */
    public function dev()
    {
        return $this->dev;
    }

    /**
     * API команды группы USER.
     *
     * @return UserCommandsGroup
     */
    public function user()
    {
        return $this->user;
    }
}
