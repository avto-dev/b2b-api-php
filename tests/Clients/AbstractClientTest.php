<?php

namespace AvtoDev\B2BApi\Tests\Clients;

use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\HttpClients\AbstractHttpClient;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;
use AvtoDev\B2BApi\Exceptions\B2BApiUnsupportedHttpClientException;

/**
 * Class AbstractClientTest.
 */
class AbstractClientTest extends AbstractClientTestCase
{
    /**
     * Тест метода `getConfigValue()`.
     *
     * @return void
     */
    public function testGetConfigValue()
    {
        foreach (['api', 'is_test', 'use_api_version', 'http_client', 'use_http_client'] as $key) {
            $this->assertArrayHasKey($key, $this->client->getConfig());
        }

        $this->assertNull($this->client->getConfig()['use_api_version']);

        $client = new AbstractClientMock([
            'some' => [
                'value' => 3.14,
            ],
        ]);
        $this->assertEquals(3.14, $client->getConfigValue('some.value'));
        $this->assertEquals(['value' => 3.14], $client->getConfigValue('some'));
        $this->assertEquals(null, $client->getConfigValue('not.exists.path'));
        $this->assertEquals(666, $client->getConfigValue('not.exists.path', 666));
    }

    /**
     * Тест корректного создания интанса http-клиента.
     */
    public function testHttpClientFactory()
    {
        $client = new AbstractClientMock([
            'use_http_client' => 'guzzle',
        ]);

        $this->assertInstanceOf(AbstractClientMock::class, $client);
    }

    /**
     * Тест корректного создания интанса http-клиента.
     */
    public function testHttpClientFactoryWithInvalidClientName()
    {
        $this->expectException(B2BApiUnsupportedHttpClientException::class);

        new AbstractClientMock([
            'use_http_client' => 'fuck you',
        ]);
    }

    /**
     * Тест метода `httpClient()`.
     *
     * @return void
     */
    public function testHttpClient()
    {
        $this->assertInstanceOf(AbstractHttpClient::class, $this->client->httpClient());
    }

    /**
     * Тест метода `getClientVersion()`.
     *
     * @return void
     */
    public function testGetClientVersion()
    {
        $this->assertEquals('0.0.1-dev', $this->client->getClientVersion());
    }

    /**
     * Тест метода `getAvailableApiVersions()`.
     *
     * @return void
     */
    public function testGetAvailableApiVersions()
    {
        $this->assertIsArray($this->client->getAvailableApiVersions());
        //$this->assertTrue(in_array('v1', $this->client->getAvailableApiVersions()));
    }

    /**
     * Тест метода `apiRequest()`.
     *
     * @return void
     */
    public function testApiRequest()
    {
        $this->assertIsArray(
            $this->client->apiRequest('get', 'some/shit', $data = [
                'some' => 'value',
            ], $data = [], $test_response = new Response(200, [], json_encode([
                'is' => 'response',
            ])))
        );
    }

    /**
     * Тест метода `apiRequest()` с некорректным json-ом в ответе.
     *
     * @return void
     */
    public function testApiRequestWithWrongJsonStringInTheResponse()
    {
        $this->expectException(B2BApiException::class);

        $this->client->apiRequest('get', 'some/shit', $data = [
            'some' => 'value',
        ], $data = [], $test_response = new Response(200, [], json_encode([
                'with' => 'wrong json string',
            ]) . '111'));
    }

    /**
     * Тест метода `apiRequest()` с некорректным ответом сервера.
     *
     * @return void
     */
    public function testApiRequestWithServerErrorInResponse()
    {
        $this->expectException(B2BApiException::class);

        $this->client->apiRequest('get', 'some/shit', $data = [
            'some' => 'value',
        ], $data = [], $test_response = new Response(500, [], json_encode([
            'is' => 'error',
        ])));
    }
}
