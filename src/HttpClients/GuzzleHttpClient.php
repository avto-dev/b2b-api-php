<?php

namespace AvtoDev\B2BApi\HttpClients;

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
        $query = $method === 'GET' ? $data : null;
        $body  = in_array($method, ['PUT', 'POST', 'DELETE']) ? json_encode($data) : null;

        return $this->http_client->request($method, (string) $uri, [
            'query'   => $query,
            'body'    => $body,
            'headers' => array_replace_recursive([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'User-Agent'   => $this->getUserAgentName(),
            ], $headers),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function httpClientFactory(...$arguments)
    {
        return new VendorGuzzleHttpClient(...$arguments);
    }
}
