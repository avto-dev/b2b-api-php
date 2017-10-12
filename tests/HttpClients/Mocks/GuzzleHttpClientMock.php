<?php

namespace AvtoDev\B2BApi\Tests\HttpClients\Mocks;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use AvtoDev\B2BApi\HttpClients\AbstractHttpClient;

/**
 * Class GuzzleHttpClientMock.
 */
class GuzzleHttpClientMock extends AbstractHttpClient
{
    /**
     * @return Client
     */
    public function getHttpClientInstance()
    {
        return $this->http_client;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, array $data = [], array $headers = [])
    {
        $this->executeCallbacks($this->before_request_callbacks, [&$method, &$uri, &$data, &$headers]);

        $mock     = new MockHandler([
            new Response(200, ['X-Fake' => true]),
        ]);
        $handler  = HandlerStack::create($mock);
        $client   = new Client(['handler' => $handler]);
        $response = $client->request($method, $uri, ['query' => $data]);

        $this->executeCallbacks($this->after_request_callbacks, [$response]);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function httpClientFactory(...$arguments)
    {
        return new Client(...$arguments);
    }
}
