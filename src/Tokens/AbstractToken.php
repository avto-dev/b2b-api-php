<?php

namespace AvtoDev\B2BApi\Tokens;

/**
 * Class AbstractToken.
 *
 * Абстрактный класс для работы с токенами.
 */
abstract class AbstractToken implements TokenInterface
{
    /**
     * Сгенерированный в конструкторе токен авторизации.
     *
     * @var string|null
     */
    protected $token;

    /**
     * Аргументы, переданные в конструктор.
     *
     * @var array
     */
    protected $arguments;

    /**
     * AbstractToken constructor.
     *
     * @param array ...$arguments
     */
    public function __construct(...$arguments)
    {
        // Сохраняем переданные в конструктор аргументы
        $this->arguments = $arguments;

        if (func_num_args() >= 2) {
            $this->refresh();
        }
    }

    /**
     * При попытке преобразовать объект в строку - возвращает токен (важно чтоб к этому моменту он был сгенерирован).
     *
     * @return null|string
     */
    public function __toString()
    {
        return $this->getToken();
    }

    /**
     * {@inheritdoc}
     */
    public function refresh(...$arguments)
    {
        $this->token = (func_num_args() >= 2)
            ? static::generate(...$arguments)
            : static::generate(...$this->arguments);

        return $this;
    }

    /**
     * Возвращает сгенерированный в конструкторе токен авторизации.
     *
     * @return null|string
     */
    public function getToken()
    {
        return $this->token;
    }
}
