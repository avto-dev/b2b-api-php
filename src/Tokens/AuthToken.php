<?php

namespace AvtoDev\B2BApi\Tokens;

use Carbon\Carbon;

/**
 * @deprecated This package is abandoned. New package is available here: <https://github.com/avtocod/b2b-api-php>
 */
class AuthToken extends AbstractToken
{
    /**
     * Префикс токена.
     */
    const TOKEN_PREFIX = 'AR-REST ';

    /**
     * Извлекает имя домена из токена авторизации (если он в нем присутствует и токен корректный).
     *
     * @param string $auth_token
     *
     * @return string|null
     */
    public static function extractDomainFromToken($auth_token)
    {
        $token_info = (array) static::parse($auth_token);
        $username   = isset($token_info['username']) ? (string) $token_info['username'] : null;

        if (mb_strlen($username) >= 3 && mb_strpos($username, '@') !== false) {
            if (($domain = mb_substr($username, mb_strpos($username, '@') + 1)) && mb_strlen($domain) >= 1) {
                return $domain;
            }
        }
    }

    /**
     * Извлекает имя пользователя из токена авторизации (если он в нем присутствует и токен корректный).
     *
     * Внимение! Извлекается имя пользователя без значения домена.
     *
     * @param string $auth_token
     *
     * @return string|null
     */
    public static function extractUsernameFromToken($auth_token)
    {
        $token_info = (array) static::parse($auth_token);
        $username   = isset($token_info['username']) ? (string) $token_info['username'] : null;

        if (\is_string($username) && mb_strlen($username) >= 1) {
            if (mb_strpos($username, '@') !== false) {
                $just_username = mb_substr($username, 0, mb_strpos($username, '@'));
                if (mb_strlen($just_username) >= 1) {
                    return $just_username;
                }
            } else {
                return $username;
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * Пример структуры возвращаемого массива:
     * <code>
     * [
     *   'username'    => '%username%',
     *   'timestamp'   => '%1234567890%',
     *   'age'         => '%1234567890%',
     *   'salted_hash' => '%salted_hash%',
     * ]
     * </code>
     */
    public static function parse($auth_token)
    {
        if (! empty($auth_token) && \is_string($auth_token)) {
            // Проверяем наличие префикса в токене
            if (mb_strpos($auth_token, trim(static::TOKEN_PREFIX)) !== false) {
                // Удаляем префикс
                $auth_token = trim(str_replace(static::TOKEN_PREFIX, '', $auth_token));
            }

            // Разбираем токен на части
            $parts       = explode(':', base64_decode($auth_token, true));
            $username    = isset($parts[0]) ? $parts[0] : null;
            $timestamp   = isset($parts[1]) ? $parts[1] : null;
            $age         = isset($parts[2]) ? $parts[2] : null;
            $salted_hash = isset($parts[3]) ? $parts[3] : null;

            // И проверяем их
            if (\is_string($username) && \is_numeric($timestamp) && \is_numeric($age) && \is_string($salted_hash)) {
                return [
                    'username'    => $username,
                    'timestamp'   => (int) $timestamp,
                    'age'         => (int) $age,
                    'salted_hash' => $salted_hash,
                ];
            }
        }

        return false;
    }

    /**
     * Метод генерации токена авторизации.
     *
     * По умолчанию токен генерируется со временем жизни - 1 сутки.
     *
     * @param string      $username  Имя пользователя
     * @param string      $password  Пароль пользователя
     * @param string|null $domain    Домен пользователя
     * @param int         $age       Время жизни токена (unix-time, в секундах)
     * @param int|null    $timestamp Временная метка (unix-time, начала жизни токена)
     *
     * @return string
     */
    public static function generate($username, $password, $domain = null, $age = 172800, $timestamp = null)
    {
        static $stack = [];

        // Время генерации токена отматываем на сутки назад, дабы покрыть возможную разницу в часовых поясах
        $timestamp  = empty($timestamp)
            ? Carbon::now()->subHours(24)->getTimestamp()
            : (int) $timestamp;
        $stack_hash = 'item_' . crc32($username . $domain . $password . $age . $timestamp);

        if (isset($stack[$stack_hash])) {
            return $stack[$stack_hash];
        } else {
            $pass_hash   = base64_encode(md5($password, true));
            $salted_hash = base64_encode(md5(implode(':', [$timestamp, $age, $pass_hash]), true));
            $token       = static::TOKEN_PREFIX . base64_encode(
                    implode(':', [
                        empty($domain) ? $username : sprintf('%s@%s', $username, $domain),
                        $timestamp,
                        $age,
                        $salted_hash,
                    ])
                );

            $stack[$stack_hash] = $token;
        }

        return $stack[$stack_hash];
    }
}
