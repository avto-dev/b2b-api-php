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
     * Пользовательские callback-функции, выполняемые ПЕРЕД осуществлением запроса.
     *
     * @var Closure[]
     */
    protected $before_request_callbacks = [];

    /**
     * Пользовательские callback-функции, выполняемые ПОСЛЕ осуществления запроса.
     *
     * @var Closure[]
     */
    protected $after_request_callbacks = [];

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
     * Добавляет пользовательский callback-метод в стек callback-функций, выполняемых ПЕРЕД осуществлением реального
     * запроса по протоколу HTTP. При их вызове в качестве аргументов прилетят:
     *  - string $method  HTTP метод
     *  - string $uri     URI строка
     *  - array  $data    Данные, передаваемые в запросе
     *  - array  $headers Заголовки запроса.
     *
     * Внимание! Объекты передаются по ссылке, что позволяем произвести их модификацию "на лету".
     *
     * @param Closure $callback
     *
     * @return static|self
     */
    public function addBeforeRequestCallback(Closure $callback)
    {
        array_push($this->before_request_callbacks, $callback);

        return $this;
    }

    /**
     * Добавляет пользовательский callback-метод в стек callback-функций, выполняемых ПОСЛЕ осуществления реального
     * запроса по протоколу HTTP. При их вызове в качестве аргумента прилетит:
     *  - ResponseInterface|mixed $response Объект-ответ HTTP клиента.
     *
     * Внимание! Объект передается по ссылке, что позволяем произвести его модификацию "на лету".
     *
     * @param Closure $callback
     *
     * @return static|self
     */
    public function addAfterRequestCallback(Closure $callback)
    {
        array_push($this->after_request_callbacks, $callback);

        return $this;
    }

    /**
     * Создает и отправляет HTTP запрос.
     *
     * ВНИМАНИЕ! При реализации данного метода следует не забыть перед и после выполнения *реального* запроса вызвать:
     * <code>
     *   $this->executeCallbacks($this->before_request_callbacks, [&$method, &$uri, &$data, &$headers]);
     *   // ... здесь осуществляется сам вызов
     *   $this->executeCallbacks($this->after_request_callbacks, [$response]);
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

    /**
     * Поочередно выполняет callback-функции из указанного стека передавая им аргументы, указанные во втором аргументе
     * метода.
     *
     * @param array $callbacks_stack
     * @param array ...$arguments
     */
    protected function executeCallbacks(array &$callbacks_stack, ...$arguments)
    {
        if (! empty($callbacks_stack)) {
            foreach ($callbacks_stack as &$callback) {
                if (is_callable($callback)) {
                    call_user_func_array($callback, ...$arguments);
                }
            }
        }
    }
}
