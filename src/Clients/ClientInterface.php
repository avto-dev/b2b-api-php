<?php

namespace AvtoDev\B2BApi\Clients;

use Psr\Http\Message\ResponseInterface;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\HttpClients\AbstractHttpClient;

/**
 * Interface ClientInterface.
 */
interface ClientInterface
{
    /**
     * Возвращает массив настроек, используемых по умолчанию.
     *
     * @return array
     */
    public function getDefaultConfig();

    /**
     * Возвращает значение из конфига, обращаясь к нему с помощью dot-нотации.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getConfigValue($path, $default = null);

    /**
     * Возвращает версию реализации клиента.
     *
     * @return string
     */
    public function getClientVersion();

    /**
     * Возвращает инстанс HTTP-клиента.
     *
     * @return AbstractHttpClient
     */
    public function httpClient();

    /**
     * Возвращает true только в том случае, если в конфигурации был указан флаг 'is_test'.
     *
     * @return bool
     */
    public function isTest();

    /**
     * Возвращает конфиг клиента (полностью, as-is).
     *
     * @return array
     */
    public function getConfig();

    /**
     * Возвращает массив всех доступных (поддерживаемых) версий API.
     *
     * @return string[]|array
     */
    public function getAvailableApiVersions();

    /**
     * Выполняет запрос к B2B API, и возвращает ответ в виде php-массива.
     *
     * @param string                 $http_method   Метод запроса
     * @param string                 $api_path      ОТНОСИТЕЛЬНЫЙ (короткий) путь запроса REST API
     * @param array|null             $data          Передаваемые данные
     * @param array|null             $headers       Заголовки запроса
     * @param ResponseInterface|null $test_response Объект-ответ, который необходимо вернуть вместо выполнения
     *                                              реального запроса к B2B API. Используется для тестов
     *
     * @throws B2BApiException
     *
     * @return array
     */
    public function apiRequest($http_method, $api_path, $data = null, $headers = null, $test_response = null);
}
