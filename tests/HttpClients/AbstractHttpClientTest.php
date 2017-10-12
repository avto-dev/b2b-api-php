<?php

namespace AvtoDev\B2BApi\Tests;

use Psr\Http\Message\ResponseInterface;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;
use AvtoDev\B2BApi\Tests\HttpClients\Mocks\GuzzleHttpClientMock;

/**
 * Class AbstractHttpClientTest.
 */
class AbstractHttpClientTest extends AbstractUnitTestCase
{
    /**
     * @var GuzzleHttpClientMock
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
        $this->http_client = new GuzzleHttpClientMock($this->api_client);
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
     * Тест метода `httpClientFactory()`.
     *
     * @return void
     */
    public function testHttpClientFactory()
    {
        // Тестируем с помощью замоканного метода `getHttpClientInstance()` ничего не создавая переж этим, так как
        // вся подготовительная работа происходит и методе `setUp()`
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $this->http_client->getHttpClientInstance());
    }

    /**
     * Тест работы callback-методов.
     *
     * @return void
     */
    public function testCallbacksAppending()
    {
        $customAfterRequestCallback = function ($response) {
            $this->assertInstanceOf(ResponseInterface::class, $response);
        };

        $customBeforeRequestCallback = function (&$method, &$uri, array &$data = [], array &$options = []) {
            $this->assertEquals($method, 'get');
            $this->assertEquals($uri, 'http://some.site');
            $this->assertEquals($data, ['foo' => 'bar']);
            $this->assertEquals($options, ['a' => 'b']);
            $uri .= '/bla_bla'; // Так как прилетают по ссылке, то callback-и могут изменять данные
        };

        $customBeforeRequestCallback2 = function (&$method, &$uri) {
            $this->assertEquals($method, 'get');
            $this->assertEquals($uri, 'http://some.site/bla_bla');
        };

        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->addAfterRequestCallback($customAfterRequestCallback)
        );
        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->addBeforeRequestCallback($customBeforeRequestCallback)
        );
        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->addBeforeRequestCallback($customBeforeRequestCallback2)
        );

        $this->http_client->request('get', 'http://some.site', ['foo' => 'bar'], ['a' => 'b']);
    }

    /**
     * Тест различных метода `getUserAgentName()`.
     *
     * @return void
     */
    public function testGetUserAgentName()
    {
        $this->assertEquals(
            'B2BApi Client/' . $this->api_client->getClientVersion() . ' curl/' . \curl_version()['version']
            . ' PHP/' . PHP_VERSION,
            $this->http_client->getUserAgentName()
        );
    }
}
