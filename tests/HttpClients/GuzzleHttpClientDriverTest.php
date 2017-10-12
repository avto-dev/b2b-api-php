<?php

namespace AvtoDev\B2BApi\Tests\HttpClients;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\HttpClients\GuzzleHttpClient;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;

/**
 * Class FakeHttpClientDriverTest.
 *
 * Тесты Guzzle драйвера HTTP клиента.
 */
class GuzzleHttpClientDriverTest extends AbstractUnitTestCase
{
    /**
     * @var GuzzleHttpClient
     */
    protected $http_client;

    /**
     * @var AbstractClientMock
     */
    protected $api_client;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->api_client  = new AbstractClientMock;
        $this->http_client = new GuzzleHttpClient($this->api_client);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->http_client);
        unset($this->api_client);

        parent::tearDown();
    }

    /**
     * Тест "реального" запроса (но это не точно (с) BRB).
     */
    public function testRealRequest()
    {
        $this->replaceHttpClientWithMock();

        $response = $this->http_client->request('get', 'https://any.url.address.here');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Pseudo-feature test passed', $response->getBody()->getContents());
    }

    /**
     * Подменяет инстанс тестируемого http-клиента - моком.
     */
    protected function replaceHttpClientWithMock()
    {
        $mock_handler = new MockHandler([
            new Response(200, [
                'x-is-testing-response' => 'yes',
                'content-type'          => 'application/json;charset=utf-8',
                'x-application-context' => 'application',
                'connection'            => 'keep-alive',
                'server'                => 'nginx',
            ], 'Pseudo-feature test passed'),
        ]);

        $this->http_client = new GuzzleHttpClient($this->api_client, [
            'handler' => HandlerStack::create($mock_handler),
        ]);
    }
}
