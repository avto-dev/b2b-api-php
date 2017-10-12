<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Report;

use Exception;
use AvtoDev\B2BApi\Traits\ConvertToArray;
use AvtoDev\B2BApi\Support\Contracts\Jsonable;
use AvtoDev\B2BApi\Support\Contracts\Arrayable;
use AvtoDev\B2BApi\Support\Contracts\Configurable;

/**
 * Class ReportSource.
 *
 * Объект - данные по источнику.
 */
class ReportSource implements Configurable, Arrayable, Jsonable
{
    use ConvertToArray;

    /**
     * Значения статусов источников.
     */
    const SOURCE_STATUS_ERROR    = 'ERROR';

    const SOURCE_STATUS_SUCCESS  = 'OK';

    const SOURCE_STATUS_PROGRESS = 'PROGRESS';

    /**
     * Имя источника.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Статус источника.
     *
     * @var string|null
     */
    protected $status;

    /**
     * Дополнительные (опциональные как правило) данные.
     *
     * @var array|null|mixed
     */
    protected $data;

    /**
     * AbstractDataType constructor.
     *
     * @param array|string|object|null $content
     */
    public function __construct($content = null)
    {
        $this->configure($content);
    }

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        foreach ((array) $this->convertToArray($content) as $key => $value) {
            try {
                switch (trim(mb_strtolower($key))) {
                    case '_id':
                    case 'name':
                        $this->name = trim((string) $value);
                        break;

                    case 'state':
                    case 'status':
                        $this->status = trim(mb_strtoupper((string) $value));
                        break;

                    case 'extra':
                    case 'optional':
                    case 'data':
                        $this->data = $value;
                        break;
                }
            } catch (Exception $e) {
                // Do nothing
            }
        }
    }

    /**
     * Источник имеет статус "в обработке"?
     *
     * @return bool
     */
    public function isProgress()
    {
        return $this->status === static::SOURCE_STATUS_PROGRESS;
    }

    /**
     * Возвращает true в том случае, если работа с источникам была завершена, и от него не следует более ждать данных.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->isError() || $this->isSuccess();
    }

    /**
     * Источник имеет статус "ошибка"?
     *
     * @return bool
     */
    public function isError()
    {
        return $this->status === static::SOURCE_STATUS_ERROR;
    }

    /**
     * Источник имеет статус "успешно завершен"?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === static::SOURCE_STATUS_SUCCESS;
    }

    /**
     * Возвращает имя источника.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает статус источника.
     *
     * @return null|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Возвращает дополнительную информацию, связанную с источником.
     *
     * @return array|null|mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'name'   => $this->name,
            'status' => $this->status,
            'data'   => $this->data,
        ];
    }
}
