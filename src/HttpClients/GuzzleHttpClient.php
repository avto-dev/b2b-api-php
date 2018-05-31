<?php

namespace AvtoDev\B2BApi\HttpClients;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as VendorGuzzleHttpClient;

/**
 * Class GuzzleHttpClient.
 *
 * Реализация HTTP-клиента, использующего пакет
 */
class GuzzleHttpClient extends AbstractHttpClient
{
    /**
     * {@inheritdoc}
     *
     * @var VendorGuzzleHttpClient
     */
    protected $http_client;

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, array $data = [], array $headers = [])
    {
        $method = mb_strtoupper(trim((string) $method));

        // Если использует GET-запрос, то передаваемые данные клиент вставит в сам запрос. Если же POST или PUT - то
        // данные будут переданы в теле самого запроса
        $query   = $method === 'GET'
            ? $data
            : null;
        $body    = in_array($method, ['PUT', 'POST', 'DELETE']) && $data !== []
            ? json_encode($data)
            : null;
        $headers = array_replace_recursive([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            'User-Agent'   => $this->getUserAgentName(),
        ], $headers);

        $this->fire('before_request', $method, $uri, $body, $headers);

        $response = ($this->endsWith($uri, 'just/for/internal/test'))
            ? new Response(200, ['X-Fake' => true], '["Just for a test"]')
            : $this->http_client->request($method, (string) $uri, [
                'query'   => $query,
                'body'    => $body,
                'headers' => $headers,
            ]);

        $this->fire('after_request', $response);

        return $response;
    }

    /**
     * Возвращает true, если строка заканчивается подстрокой $needle.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    protected function endsWith($haystack, $needle)
    {
        $length = mb_strlen($needle);

        return $length === 0 || (mb_substr($haystack, -$length) === $needle);
    }

    /**
     * {@inheritdoc}
     */
    protected function httpClientFactory(...$arguments)
    {
        return new VendorGuzzleHttpClient(...$arguments);
    }
}
