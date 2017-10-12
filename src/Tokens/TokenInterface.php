<?php

namespace AvtoDev\B2BApi\Tokens;

/**
 * Interface TokenInterface.
 */
interface TokenInterface
{
    /**
     * Конструктор.
     *
     * @param string[] ...$arguments
     */
    public function __construct(...$arguments);

    /**
     * Разбирает переданный методу токен, возвращая информацию содержащуюся в нем в виде структурированного массива.
     *
     * В случае ошибки разбора вернет false.
     *
     * @param string $auth_token
     *
     * @return array|false
     */
    public static function parse($auth_token);

    /**
     * Метод генерации токена авторизации.
     *
     * @param string $username Имя пользователя
     * @param string $password Пароль пользователя
     *
     * @return string
     */
    public static function generate($username, $password);

    /**
     * Обновляет токен (используя аргументы, переданные в конструкторе, если в метод они не были переданы).
     *
     * @param string[] ...$arguments
     *
     * @return self|static
     */
    public function refresh(...$arguments);
}
