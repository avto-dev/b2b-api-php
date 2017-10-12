<?php

namespace AvtoDev\B2BApi\Clients;

use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AvtoDev\B2BApi\Responses\ResponseInterface as B2BApiResponseInterface;
use GuzzleHttp\Exception\RequestException;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\HttpClients\GuzzleHttpClient;
use AvtoDev\B2BApi\HttpClients\AbstractHttpClient;
use AvtoDev\B2BApi\Traits\StackValuesDotAccessible;
use AvtoDev\B2BApi\Exceptions\B2BApiUnsupportedHttpClientException;

/**
 * Class AbstractClient.
 */
abstract class AbstractClient implements ClientInterface
{
    use StackValuesDotAccessible {
        getStackValueWithDot as protected;
        getStackValueWithDot as getConfigValue;
    }

    /**
     * Конфигурация клиента.
     *
     * Указанные ниже опции являются не зависимые от реализации клиента и используются по-умолчанию.
     *
     * @var array
     */
    protected $config = [

        /*
         * Настройки API поставщика
         */
        'api'             => [
            'versions' => [
            //    'v1' => [
            //        'base_uri' => null,
            //    ],
            ],
        ],

        // Используемая версия API поставщика (значение должно быть переопределено реализацией). Значение - имя ключа
        // из массива api.versions что описаны выше
        'use_api_version' => null,

        /*
         * Настройки клиента
         */
        'client'          => [
            // Домен пользователя
            'domain'   => null,
            // Имя пользователя
            'username' => null,
            // Пароль пользователя
            'password' => null,
        ],

        /*
         * Настройки http-клиентов
         */
        'http_client'     => [
            /*
             * Поддерживаемые HTTP-клиенты
             */
            'guzzle' => [
                /*
                 * Настройки, передаваемые в конструктор http-клиента Guzzle.
                 *
                 * @see <http://docs.guzzlephp.org/en/stable/request-options.html>
                 */
                'constructor' => [
                    // Верифицировать ли SSL сертификат сервера?
                    'verify' => true,
                ],
            ],
        ],

        // Используемый http-клиент. Значение - имя ключа из массива http_client что описаны выше
        'use_http_client' => 'guzzle',

        // Опция, говорящая о том, что клиент работает в режиме тестирования (имитации осуществления запросов)
        'is_test'         => false,

    ];

    /**
     * @var AbstractHttpClient
     */
    protected $http_client;

    /**
     * AbstractClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config      = array_replace_recursive($this->config, $this->getDefaultConfig(), $config);
        $this->http_client = $this->httpClientFactory();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getDefaultConfig();

    /**
     * {@inheritdoc}
     */
    public function isTest()
    {
        return $this->getConfigValue('is_test', false) === true;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getClientVersion();

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableApiVersions()
    {
        return array_keys($this->getConfigValue('api.versions'));
    }

    /**
     * {@inheritdoc}
     */
    public function apiRequest($http_method, $api_path, $data = null, $headers = null, $test_response = null)
    {
        $data    = is_array($data) ? $data : [];
        $headers = is_array($headers) ? $headers : [];
        $uri     = $this->getApiRequestUri($api_path);

        try {
            // Временная метка начала осуществления запроса
            $now = microtime(true);

            // Если в метод был передан объект-ответ, который надо использовать как ответ от B2B API - то используем его
            $response = ($test_response instanceof ResponseInterface)
                ? clone $test_response
                : $this->http_client->request($http_method, $uri, $data, $headers);

            // Считаем время исполнения запроса (в секундах с дробной частью)
            $duration = round((microtime(true) - $now), 4);

            // Это условие, в основном, сделано для тестов
            if ($test_response instanceof ResponseInterface && ($code = $test_response->getStatusCode() >= 400)) {
                throw new RequestException(
                    sprintf('Wrong response code: %d', $code),
                    new Request($http_method, $uri),
                    $test_response
                );
            }

            $response_array = json_decode($content = $response->getBody()->getContents(), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return array_replace($response_array, [
                    // Добавляем время исполнения запроса в ответ
                    B2BApiResponseInterface::REQUEST_DURATION_KEY_NAME => $duration,
                ]);
            } else {
                throw new B2BApiException(sprintf('Invalid JSON string received: "%s"', $content));
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $request  = $e->getRequest();
            throw new B2BApiException(sprintf(
                'Request to the B2B API (path: "%s", body: "%s") failed with message: "%s"',
                $request instanceof RequestInterface ? $request->getUri() : null,
                $request instanceof RequestInterface ? $request->getBody()->getContents() : null,
                $e->getMessage()
            ), $response instanceof ResponseInterface ? $response->getStatusCode() : $e->getCode(), $e);
        } catch (Exception $e) {
            throw new B2BApiException(sprintf(
                'Request to the B2B API failed with message: "%s"', $e->getMessage()
            ), $e->getCode(), $e);
        }
    }

    /**
     * Факторка, возвращающая инстанс http-клиента.
     *
     * @throws B2BApiUnsupportedHttpClientException
     *
     * @return AbstractHttpClient
     */
    protected function httpClientFactory()
    {
        switch ($use = $this->getConfigValue('use_http_client')) {
            case 'guzzle':
                return new GuzzleHttpClient($this, $this->getConfigValue('http_client.guzzle.constructor'));

            default:
                throw new B2BApiUnsupportedHttpClientException(sprintf(
                    'Unsupported HTTP client: "%s"', $use
                ));
        }
    }

    /**
     * Возвращает абсолютный HTTP путь к методу API.
     *
     * @param string $api_path
     *
     * @return string
     */
    protected function getApiRequestUri($api_path)
    {
        static $base_uri = null;

        if (empty($base_uri)) {
            // Инициализируем базовый URI
            $base_uri = rtrim(
                $this->getConfigValue("api.versions.{$this->getConfigValue('use_api_version')}.base_uri"),
                '\\/ '
            );
        }

        return $base_uri . '/' . ltrim((string) $api_path, '\\/ ');
    }

    /**
     * {@inheritdoc}
     *
     * @see StackValuesDotAccessible::getAccessorStack()
     */
    protected function getAccessorStack()
    {
        return $this->config;
    }
}
