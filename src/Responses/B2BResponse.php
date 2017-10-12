<?php

namespace AvtoDev\B2BApi\Responses;

use Countable;

/**
 * Class B2BResponse.
 *
 * Объект ответа от сервиса B2B.
 */
class B2BResponse extends AbstractResponse implements Countable
{
    /**
     * Статусы ответов от B2B. Возвращаются в корневом ключе "state".
     */
    const RESPONSE_STATE_SUCCESS = 'ok';

    const RESPONSE_STATE_FAILED  = 'fail';

    /**
     * @var DataCollection
     */
    protected $data;

    /**
     * Продолжительность запроса для получения данного ответа (в секундах).
     *
     * @var float
     */
    protected $request_duration = 0.0;

    /**
     * {@inheritdoc}
     */
    public function __construct($input = null)
    {
        parent::__construct($input);

        $this->data = new DataCollection(isset($this->raw['data']) ? $this->raw['data'] : null);

        // Если есть данные о времени исполнения запроса (ищем по фиксированному ключу)
        if (is_array($input) && isset($input[static::REQUEST_DURATION_KEY_NAME])) {
            $this->request_duration = (float) $input[static::REQUEST_DURATION_KEY_NAME];
        }
    }

    /**
     * Возвращает время выполнения запроса непосредственно к сервису B2B.
     *
     * @return float
     */
    public function getRequestDuration()
    {
        return $this->request_duration;
    }

    /**
     * Метод, реализующий доступ к коллекции данных ответа.
     *
     * @return DataCollection
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Возвращает true в том случае, если B2B вернул корректный ответ.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getState() === static::RESPONSE_STATE_SUCCESS;
    }

    /**
     * Возвращает true в том случае, если B2B вернул НЕ корректный ответ.
     *
     * @return bool
     */
    public function isFailed()
    {
        return $this->getState() === static::RESPONSE_STATE_FAILED || ! $this->isSuccess();
    }

    /**
     * Возвращает статус ответа от B2B.
     *
     * @return null|string
     */
    public function getState()
    {
        return (isset($this->raw['state']) && ! empty($this->raw['state']))
            ? (string) $this->raw['state']
            : null;
    }

    /**
     * Возвращает количество вхождений в ответе от B2B.
     *
     * @return int|null
     */
    public function count()
    {
        return isset($this->raw['size'])
            ? (int) $this->raw['size']
            : (isset($this->raw['data']) && is_array($this->raw['data'])
                ? count($this->raw['data'])
                : null);
    }

    /**
     * Возвращает время ответа (временную метку) ответа от B2B в виде Carbon-объекта, или null в противном случае.
     *
     * @return \Carbon\Carbon|null
     */
    public function getRespondedAt()
    {
        return isset($this->raw['stamp'])
            ? $this->convertToCarbon($this->raw['stamp'])
            : null;
    }
}
