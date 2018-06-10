<?php

namespace AvtoDev\B2BApi\Tests;

use Psr\Http\Message\ResponseInterface;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;
use AvtoDev\B2BApi\Tests\HttpClients\Mocks\GuzzleHttpClientMock;

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
        $counter = 0;

        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->on('after_request',
                function ($response) use (&$counter) {
                    $this->assertInstanceOf(ResponseInterface::class, $response);
                    $counter++;
                }
            )
        );

        // В новый стек
        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->on('some', function () use (&$counter) {
                $counter--; // Не должно само произойти
            })
        );

        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->on('before_request',
                function (&$method, &$uri, array &$data = [], array &$options = []) use (&$counter) {
                    $this->assertEquals($method, 'get');
                    $this->assertEquals($uri, 'http://some.site');
                    $this->assertEquals($data, ['foo' => 'bar']);
                    $this->assertEquals($options, ['a' => 'b']);
                    $uri .= '/bla_bla'; // Так как прилетают по ссылке, то callback-и могут изменять данные
                    $counter++;
                }
            )
        );

        $this->assertInstanceOf(
            GuzzleHttpClientMock::class,
            $this->http_client->on('before_request',
                function (&$method, &$uri) use (&$counter) {
                    $this->assertEquals($method, 'get');
                    $this->assertEquals($uri, 'http://some.site/bla_bla');
                    $counter++;
                }
            )
        );

        $this->http_client->request('get', 'http://some.site', ['foo' => 'bar'], ['a' => 'b']);

        $this->assertEquals(3, $counter);

        // И только теперь событие должно сработать
        $this->http_client->fire('some');
        $this->assertEquals(2, $counter);
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
