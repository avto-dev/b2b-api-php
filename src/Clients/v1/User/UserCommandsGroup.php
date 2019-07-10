<?php

namespace AvtoDev\B2BApi\Clients\v1\User;

use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Tokens\AuthToken;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Clients\ClientInterface;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;
use AvtoDev\B2BApi\Clients\v1\User\Report\ReportCommandsGroup;

/**
 * @deprecated This package is abandoned. New package is available here: <https://github.com/avtocod/b2b-api-php>
 */
class UserCommandsGroup extends AbstractApiCommandsGroup
{
    /**
     * @var ReportCommandsGroup
     */
    protected $report;

    /**
     * UserCommandsGroup constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        parent::__construct($client);

        $this->report = new ReportCommandsGroup($client);
    }

    /**
     * @return ReportCommandsGroup
     */
    public function report()
    {
        return $this->report;
    }

    /**
     * Информация о текущем пользователе.
     *
     * @param string $auth_token Токен безопасности
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function info($auth_token)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            'user',
            null,
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest() ? new Response(
                200,
                $this->getTestingResponseHeaders(),
                str_replace(
                    [
                        '%domain%',
                        '%username%',
                    ],
                    [
                        AuthToken::extractDomainFromToken($auth_token),
                        AuthToken::extractUsernameFromToken($auth_token),
                    ],
                    file_get_contents(__DIR__ . '/user.json')
                )
            ) : null
        ));
    }

    /**
     * Проверка доступности квоты по UID-у типа отчета.
     *
     * @param string $auth_token      Токен безопасности
     * @param string $report_type_uid UID типа отчета
     * @param bool   $detailed
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function balance($auth_token, $report_type_uid, $detailed = false)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            sprintf('user/balance/%s', urlencode($report_type_uid)),
            [
                '_detailed' => (bool) $detailed ? 'true' : 'false',
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest() ? new Response(
                200,
                $this->getTestingResponseHeaders(),
                str_replace(
                    [
                        '%domain%',
                        '%default_report_type_uid%',
                    ],
                    [
                        AuthToken::extractDomainFromToken($auth_token),
                        $report_type_uid,
                    ],
                    file_get_contents(__DIR__ . '/balance.json')
                )
            ) : null
        ));
    }
}
