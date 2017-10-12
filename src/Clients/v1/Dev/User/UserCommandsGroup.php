<?php

namespace AvtoDev\B2BApi\Clients\v1\Dev\User;

use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;

/**
 * Class UserCommandsGroup.
 *
 * API команды группы Dev\User.
 */
class UserCommandsGroup extends AbstractApiCommandsGroup
{
    /**
     * Запрос отчетов в режиме имитации.
     *
     * @param string $auth_token   Токен безопасности
     * @param string $report_uid   UID отчета
     * @param bool   $detailed     Детализация
     * @param bool   $with_content Включить контент в ответ
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function reports($auth_token, $report_uid, $detailed = true, $with_content = true)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            sprintf('dev/user/reports/%s', urlencode((string) $report_uid)),
            [
                '_detailed' => (bool) $detailed ? 'true' : 'false',
                '_content'  => (bool) $with_content ? 'true' : 'false',
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest() ? new Response(
                200, $this->getTestingResponseHeaders(), file_get_contents(__DIR__ . '/reports.json')
            ) : null
        ));
    }

    /**
     * Перегенерация отчета в режиме имитации.
     *
     * @param string $auth_token Токен безопасности
     * @param string $report_uid UID отчета
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function refresh($auth_token, $report_uid)
    {
        return new B2BResponse($this->client->apiRequest(
            'post',
            sprintf('dev/user/reports/%s/_refresh', urlencode($report_uid)),
            null,
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest() ? new Response(
                // На момент написания этих строк B2B не возвращал корректный ответ, а именно эту ошибку
                500, $this->getTestingResponseHeaders(), file_get_contents(__DIR__ . '/refresh.json')
            ) : null
        ));
    }
}
