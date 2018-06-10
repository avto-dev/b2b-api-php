<?php

namespace AvtoDev\B2BApi\Tests\HttpClients\Mocks;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use AvtoDev\B2BApi\HttpClients\AbstractHttpClient;

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
        $mock     = new MockHandler([
            new Response(200, ['X-Fake' => true]),
        ]);
        $handler  = HandlerStack::create($mock);
        $client   = new Client(['handler' => $handler]);

        $this->fire('before_request', $method, $uri, $data, $headers);

        $response = $client->request($method, $uri, ['query' => $data]);

        $this->fire('after_request', $response);

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
