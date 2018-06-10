<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Report;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUid;
use AvtoDev\B2BApi\Responses\DataTypes\AbstractDataType;

class ReportStatusData extends AbstractDataType
{
    use WithUid;

    /**
     * Этот отчет является новым?
     *
     * @return bool|null
     */
    public function isNew()
    {
        return $this->getContentValue('isnew', null);
    }

    /**
     * Возвращает значение 'process_request_uid'?
     *
     * @return bool|null
     */
    public function getProcessRequestUid()
    {
        return $this->getContentValue('process_request_uid', null);
    }

    /**
     * Возвращает ориентировочные дату/время, когда отчет будет готов.
     *
     * @return Carbon|null
     */
    public function getSuggestGet()
    {
        return ! empty($value = $this->getContentValue('suggest_get', null))
            ? $this->convertToCarbon($value)
            : null;
    }
}
