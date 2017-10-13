<?php

namespace AvtoDev\B2BApi\HttpClients;

use Closure;
use Psr\Http\Message\ResponseInterface;
use AvtoDev\B2BApi\Clients\ClientInterface;

/**
 * Class AbstractHttpClient.
 *
 * Абстрактный класс HTTP-клиента.
 */
abstract class AbstractHttpClient
{
    /**
     * Инстанс API клиента, который создал данный HTTP-клиент.
     *
     * @var ClientInterface
     */
    protected $api_client;

    /**
     * Инстанс HTTP-клиента.
     *
     * @var mixed
     */
    protected $http_client;

    /**
     * Стек callback-функций.
     *
     * @var array[]
     */
    protected $callbacks = [
        'before_request' => [],
        'after_request'  => [],
    ];

    /**
     * AbstractHttpClient constructor.
     *
     * @param ClientInterface $api_client
     * @param array           ...$http_client_constructor_arguments
     */
    public function __construct(ClientInterface $api_client, ...$http_client_constructor_arguments)
    {
        $this->api_client  = $api_client;
        $this->http_client = $this->httpClientFactory(...$http_client_constructor_arguments);
    }

    /**
     * Добавляет именованное событие в стек.
     *
     * @param string  $event_type По умолчанию: 'before_request', 'after_request'
     * @param Closure $callback
     *
     * @return static|self
     */
    public function on($event_type, Closure $callback)
    {
        $event_type = (string) $event_type;

        // Инициализируем стек
        if (! isset($this->callbacks[$event_type])) {
            $this->callbacks[$event_type] = [];
        }

        array_push($this->callbacks[$event_type], $callback);

        return $this;
    }

    /**
     * Выполняет все события, что были помещены в именованный стек.
     *
     * @param string $event_type   По умолчанию: 'before_request', 'after_request'
     * @param array  ...$arguments
     */
    public function fire($event_type, ...$arguments)
    {
        $event_type = (string) $event_type;

        if (isset($this->callbacks[$event_type])) {
            foreach ($this->callbacks[$event_type] as &$callback) {
                $callback(...$arguments);
            }
        }
    }

    /**
     * Создает и отправляет HTTP запрос.
     *
     * ВНИМАНИЕ! При реализации данного метода следует не забыть перед и после выполнения *реального* запроса вызвать:
     * <code>
     *   $this->fire('before_request', $method, $uri, $data, $headers);
     *   // ... здесь осуществляется сам вызов
     *   $this->fire('after_request', $response);
     * </code>
     *
     * @param string $method  HTTP метод
     * @param string $uri     URI строка
     * @param array  $data    Данные, передаваемые в запросе
     * @param array  $headers Заголовки запроса
     *
     * @return ResponseInterface|mixed
     */
    abstract public function request($method, $uri, array $data = [], array $headers = []);

    /**
     * Возвращает строку User-Agent, используемую по умолчанию.
     *
     * @return string
     */
    public function getUserAgentName()
    {
        static $user_agent_name = '';

        if (empty($user_agent_name)) {
            $user_agent_name = 'B2BApi Client/' . $this->api_client->getClientVersion() . ' curl/'
                . \curl_version()['version'] . ' PHP/' . PHP_VERSION;
        }

        return $user_agent_name;
    }

    /**
     * Возвращает инстанс самого HTTP-клиента.
     *
     * @param array ...$arguments
     *
     * @return mixed
     */
    abstract protected function httpClientFactory(...$arguments);
}
