<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\User;

use AvtoDev\B2BApi\Responses\DataTypes\AbstractDataType;

/**
 * Class BalanceData.
 */
class BalanceData extends AbstractDataType
{
    /**
     * Типы баланса.
     */
    const BALANCE_TYPE_DAY   = 'DAY';

    const BALANCE_TYPE_MONTH = 'MONTH';

    const BALANCE_TYPE_TOTAL = 'TOTAL';

    /**
     * Возвращает true, если это суточный баланс.
     *
     * @return bool
     */
    public function isDailyBalance()
    {
        return $this->getBalanceType() === static::BALANCE_TYPE_DAY;
    }

    /**
     * Возвращает true, если это месячный баланс.
     *
     * @return bool
     */
    public function isMonthlyBalance()
    {
        return $this->getBalanceType() === static::BALANCE_TYPE_MONTH;
    }

    /**
     * Возвращает true, если это общий баланс.
     *
     * @return bool
     */
    public function isTotalBalance()
    {
        return $this->getBalanceType() === static::BALANCE_TYPE_TOTAL;
    }

    /**
     * Возвращает UID типа отчета.
     *
     * @return null|string
     */
    public function getReportTypeUid()
    {
        return $this->getContentValue('report_type_uid', null);
    }

    /**
     * Возвращает тип баланса.
     *
     * @return null|string
     */
    public function getBalanceType()
    {
        return $this->getContentValue('balance_type', null);
    }

    /**
     * Возвращает значение "quote_init".
     *
     * @return null|string
     */
    public function getQuoteInit()
    {
        return $this->getContentValue('quote_init', null);
    }

    /**
     * Возвращает значение "quote_up".
     *
     * @return null|string
     */
    public function getQuoteUp()
    {
        return $this->getContentValue('quote_up', null);
    }

    /**
     * Возвращает значение "quote_use".
     *
     * @return null|string
     */
    public function getQuoteUse()
    {
        return $this->getContentValue('quote_use', null);
    }
}
