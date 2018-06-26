<?php

namespace AvtoDev\B2BApi\Clients\v1\User\Report;

use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Exceptions\B2BApiInvalidArgumentException;
use AvtoDev\B2BApi\References\QueryTypes;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Tokens\AuthToken;
use GuzzleHttp\Psr7\Response;

/**
 * API команды группы User\Report.
 */
class ReportCommandsGroup extends AbstractApiCommandsGroup
{
    /**
     * Получение типов отчетов, доступных конкретному пользователю.
     *
     * @param string $auth_token Токен безопасности
     * @param int    $limit
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function types($auth_token, $limit = 200)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            'user/report_types',
            [
                '_query'        => '_all',        // Описатель запроса (язык зависит от контекста)
                '_size'         => (int) $limit,  // Максимальное количество данных в выдаче, размер страницы
                '_offset'       => 0,             // Смещение окна записей относительно всей выборки
                '_page'         => 1,             // Номер страницы
                '_sort'         => '-created_at', // Настройки сортировки
                '_calc_total'   => 'true',        // Вычислять ли общее количество
                '_can_generate' => 'true',        // Может ли пользователь генерировать отчеты для данного типа
                '_content'      => 'true',        // Признак наличия контента
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest()
                ? new Response(
                200,
                $this->getTestingResponseHeaders(),
                str_replace(
                    [
                        '%domain%',
                    ],
                    [
                        AuthToken::extractDomainFromToken($auth_token),
                    ],
                    file_get_contents(__DIR__ . '/report_types.json')
                )
            )
                : null
        ));
    }

    /**
     * Получение списка уже созданных отчетов.
     *
     * @param string $auth_token Токен безопасности
     * @param int    $limit
     * @param int    $offset
     * @param int    $page
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function getAll($auth_token, $limit = 200, $offset = 0, $page = 1)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            'user/reports',
            [
                '_query'      => '_all',        // Описатель запроса (язык зависит от контекста)
                '_size'       => (int) $limit,  // Максимальное количество данных в выдаче, размер страницы
                '_offset'     => (int) $offset, // Смещение окна записей относительно всей выборки
                '_page'       => (int) $page,   // Номер страницы
                '_sort'       => '-created_at', // Настройки сортировки
                '_calc_total' => 'true',        // Вычислять ли общее количество
                '_detailed'   => 'true',
                '_content'    => 'true',        // Признак наличия контента
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest()
                ? new Response(
                200,
                $this->getTestingResponseHeaders(),
                str_replace(
                    [
                        '%domain%',
                    ],
                    [
                        AuthToken::extractDomainFromToken($auth_token),
                    ],
                    file_get_contents(__DIR__ . '/reports.json')
                )
            )
                : null
        ));
    }

    /**
     * Получение имеющегося отчета.
     *
     * @param string $auth_token   Токен безопасности
     * @param string $report_uid   UID отчета
     * @param bool   $detailed
     * @param bool   $with_content Признак наличия контента
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function get($auth_token, $report_uid, $detailed = true, $with_content = true)
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            sprintf('user/reports/%s', urlencode($report_uid)),
            [
                '_detailed' => (bool) $detailed
                    ? 'true'
                    : 'false',
                '_content'  => (bool) $with_content
                    ? 'true'
                    : 'false',
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest()
                ? new Response(
                200,
                $this->getTestingResponseHeaders(),
                str_replace(
                    [
                        '%domain%',
                        '%some_report_type_uid_with_domain%',
                    ],
                    [
                        AuthToken::extractDomainFromToken($auth_token),
                        $report_uid,
                    ],
                    file_get_contents(__DIR__ . '/report.json')
                )
            )
                : null
        ));
    }

    /**
     * Генерация нового отчета.
     *
     * ВНИМАНИЕ! Данная операция спишет с баланса один отчет.
     *
     * @param string $auth_token      Токен безопасности
     * @param string $query_type      Тип запрашиваемой сущности
     * @param string $query_id        Значение запрашиваемой сущности
     * @param string $report_type_uid UID типа отчета
     * @param bool   $is_force        Нужно ли перегенерировать отчет если он уже существует?
     *
     * @throws B2BApiInvalidArgumentException
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function make($auth_token, $query_type, $query_id, $report_type_uid, $is_force = false)
    {
        if (! QueryTypes::has($query_type)) {
            throw new B2BApiInvalidArgumentException(sprintf(
                'Passed query type "%s" is not valid',
                $query_type
            ));
        }

        return new B2BResponse($this->client->apiRequest(
            'post',
            sprintf('user/reports/%s/_make', urlencode($report_type_uid)),
            [
                'queryType' => (string) $query_type,
                'query'     => (string) $query_id,
                'options'   => [
                    'FORCE' => (bool) $is_force,
                ],
            ],
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest()
                ? new Response(
                200, $this->getTestingResponseHeaders(), file_get_contents(__DIR__ . '/make.json')
            )
                : null
        ));
    }

    /**
     * Запрос на обновление данных в отчете.
     *
     * ВНИМАНИЕ! Данная операция спишет с баланса один отчет.
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
            sprintf('user/reports/%s/_refresh', urlencode($report_uid)),
            null,
            [
                'Authorization' => (string) $auth_token,
            ],
            $this->client->isTest()
                ? new Response(
                200, $this->getTestingResponseHeaders(), file_get_contents(__DIR__ . '/refresh.json')
            )
                : null
        ));
    }
}
