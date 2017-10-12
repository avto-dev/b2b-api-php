<?php

namespace AvtoDev\B2BApi\Clients\v1\Dev;

use DateTime;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Clients\ClientInterface;
use AvtoDev\B2BApi\Exceptions\B2BApiException;
use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;
use AvtoDev\B2BApi\Clients\v1\Dev\User\UserCommandsGroup;

/**
 * Class DevCommandsGroup.
 *
 * API команды группы Dev.
 */
class DevCommandsGroup extends AbstractApiCommandsGroup
{
    /**
     * @var UserCommandsGroup
     */
    protected $user;

    /**
     * DevCommandsGroup constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        parent::__construct($client);

        $this->user = new UserCommandsGroup($client);
    }

    /**
     * @return UserCommandsGroup
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Проверка соединения.
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function ping()
    {
        return new B2BResponse($this->client->apiRequest(
            'get',
            'dev/ping',
            [
                'value' => $test_value = 'pong',
            ],
            [],
            $this->client->isTest() ? new Response(
                200, $this->getTestingResponseHeaders(), json_encode([
                'value' => $test_value,
                'in'    => 0,
                'out'   => $ts = Carbon::now()->addSeconds(rand(0, 1))->getTimestamp(),
                'delay' => $ts + rand(0, 2),
            ])) : null
        ));
    }

    /**
     * Отладка формирования токена.
     *
     * @param string                          $username  Идентификатор пользователя
     * @param string                          $password  Пароль (или md5 пароля)
     * @param bool                            $is_hash   Признак использования MD5 пароля
     * @param null|Carbon|DateTime|int|string $date_from Дата начала действия токена с точностью до секунды
     * @param int                             $age       Срок действия токена (в секундах)
     *
     * @throws B2BApiException
     *
     * @return B2BResponse
     */
    public function token($username, $password, $is_hash = false, $date_from = null, $age = 60)
    {
        if (is_string($username) && ! empty($username)) {
            if (is_string($password) && ! empty($password)) {
                // Типизируем значения
                $date_from = is_null($date_from) ? Carbon::now() : $this->convertToCarbon($date_from);
                $is_hash   = boolval($is_hash);
                $age       = intval($age, 10);

                if (! ($date_from instanceof Carbon)) {
                    throw new B2BApiException('Cannot convert passed date to Carbon object');
                }

                if ($age <= 1) {
                    throw new B2BApiException('Age cannot be less then 1 second');
                }

                return new B2BResponse($this->client->apiRequest(
                    'get',
                    'dev/token',
                    [
                        'user'    => $username,
                        'pass'    => $password,
                        'is_hash' => $is_hash,
                        'date'    => $date_from->toIso8601String(),
                        'age'     => $age,
                    ],
                    [],
                    $this->client->isTest() ? new Response(
                        200, $this->getTestingResponseHeaders(), json_encode([
                        'user'             => $username,
                        'pass'             => $password,
                        'pass_hash'        => base64_encode(md5($password, true)),
                        'date'             => Carbon::now()->toIso8601String(),
                        'stamp'            => Carbon::now()->getTimestamp(),
                        'age'              => $age,
                        'salt'             => $not_available = 'NOT:AVAILABLE:DURING:TESTING',
                        'salted_pass_hash' => $not_available,
                        'raw_token'        => $not_available,
                        'token'            => $not_available,
                        'header'           => $not_available,
                    ])) : null
                ));
            } else {
                throw new B2BApiException('Invalid or empty password passed');
            }
        } else {
            throw new B2BApiException('Invalid or empty username passed');
        }
    }
}
