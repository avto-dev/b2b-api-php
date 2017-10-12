<?php

namespace AvtoDev\B2BApi\Clients;

use AvtoDev\B2BApi\Traits\ConvertToCarbon;

/**
 * Class AbstractApiCommandsGroup.
 */
abstract class AbstractApiCommandsGroup
{
    use ConvertToCarbon;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * AbstractApiCommandsGroup constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Возвращает массив заголовков, которые используются для тестовых ответов B2B API.
     *
     * @return string[]
     */
    protected function getTestingResponseHeaders()
    {
        return [
            'x-is-testing-response' => 'yes',
            'content-type'          => 'application/json;charset=utf-8',
            'x-application-context' => 'application',
            'connection'            => 'keep-alive',
            'server'                => 'nginx',
        ];
    }
}
