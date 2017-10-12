<?php

namespace AvtoDev\B2BApi\Responses;

use Closure;
use Iterator;
use Countable;
use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Traits\ConvertToArray;
use AvtoDev\B2BApi\Support\Contracts\Configurable;
use AvtoDev\B2BApi\Responses\DataTypes\UnknownDataType;
use AvtoDev\B2BApi\Responses\DataTypes\User\BalanceData;
use AvtoDev\B2BApi\Responses\DataTypes\DataTypeInterface;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;
use AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportStatusData;
use AvtoDev\B2BApi\Responses\DataTypes\ReportType\ReportTypeData;

/**
 * Class DataCollection.
 */
class DataCollection implements Configurable, Countable, Iterator
{
    use ConvertToArray;

    /**
     * Типы "данных" (которые прилетели по ключу "data").
     */
    const DATA_TYPE_UNKNOWN      = 'unknown';

    const DATA_TYPE_REPORT       = 'report';

    const DATA_TYPE_USER_INFO    = 'user_info';

    const DATA_TYPE_USER_BALANCE = 'user_balance';

    const DATA_TYPE_REPORT_TYPE  = 'report_type';

    const DATA_TYPE_REPORT_MAKE  = 'report_make';

    /**
     * @var DataTypeInterface[]|array
     */
    protected $stack = [];

    /**
     * DataCollection constructor.
     *
     * В него должен прилетать НЕ ответ от B2B в виде массива целиком, а только данные по ключу "data".
     *
     * @param array|string|Response|object|null $input
     */
    public function __construct($input = null)
    {
        $this->configure($input);
    }

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        foreach ((array) $this->convertToArray($content) as $data_block) {
            switch ($this->getDataType($data_block)) {
                case static::DATA_TYPE_REPORT:
                    array_push($this->stack, new ReportData($data_block));
                    break;

                case static::DATA_TYPE_USER_INFO:
                    array_push($this->stack, new UserInfoData($data_block));
                    break;

                case static::DATA_TYPE_USER_BALANCE:
                    array_push($this->stack, new BalanceData($data_block));
                    break;

                case static::DATA_TYPE_REPORT_TYPE:
                    array_push($this->stack, new ReportTypeData($data_block));
                    break;

                case static::DATA_TYPE_REPORT_MAKE:
                    array_push($this->stack, new ReportStatusData($data_block));
                    break;

                default:
                    array_push($this->stack, new UnknownDataType($data_block));
            }
        }
    }

    /**
     * Возвращает все элементы стека данных.
     *
     * @return DataTypeInterface[]|array
     */
    public function all()
    {
        return $this->stack;
    }

    /**
     * Возвращает первый элемент в стеке данных, если он имеется. В противном случае - вернет null.
     *
     * @return DataTypeInterface|null
     */
    public function first()
    {
        return isset($this->stack[0]) ? $this->stack[0] : null;
    }

    /**
     * Возвращает количество элементов в стеке.
     *
     * @return int
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
     * Перебирает все элементы стека, выполняя для каждого элемента переданную лямбду.
     *
     * @param Closure $closure
     *
     * @return static|self
     */
    public function each(Closure $closure)
    {
        foreach ($this->stack as &$item) {
            $closure($item);
        }

        return $this;
    }

    /**
     * Возвращает true в том случае, если стек пустой.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->stack);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return ($key = $this->key()) && ! is_null($key) && $key !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->stack);
    }

    /**
     * Определяет тип данных, которые содержатся в ответе от сервиса B2B.
     *
     * @param array|mixed $data_block
     *
     * @return string
     */
    protected function getDataType($data_block)
    {
        if (is_array($data_block) && ! empty($data_block)) {
            if (isset($data_block['report_type_uid']) && isset($data_block['query'])) {
                return static::DATA_TYPE_REPORT;
            } elseif (isset($data_block['login']) && isset($data_block['state']) && isset($data_block['roles'])) {
                return static::DATA_TYPE_USER_INFO;
            } elseif (isset($data_block['balance_type']) && isset($data_block['report_type_uid'])) {
                return static::DATA_TYPE_USER_BALANCE;
            } elseif (isset($data_block['state']) && isset($data_block['total_quote']) && isset($data_block['content'])) {
                return static::DATA_TYPE_REPORT_TYPE;
            } elseif (isset($data_block['isnew']) && isset($data_block['suggest_get'])) {
                return static::DATA_TYPE_REPORT_MAKE;
            }
        }

        return static::DATA_TYPE_UNKNOWN;
    }
}
