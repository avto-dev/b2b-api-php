<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\ReportType;

use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUid;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithName;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithTags;
use AvtoDev\B2BApi\Responses\DataTypes\AbstractDataType;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithState;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithActive;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithComment;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithCreated;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUpdated;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithDomainUid;

/**
 * @deprecated This package is abandoned. New package is available here: <https://github.com/avtocod/b2b-api-php>
 */
class ReportTypeData extends AbstractDataType
{
    use WithState, WithDomainUid, WithUid, WithName, WithComment, WithTags, WithCreated, WithUpdated, WithActive;

    /**
     * Типы статусов.
     */
    const STATE_PUBLISHED = 'PUBLISHED';

    /**
     * Возвращает true, если данный тип отчета имеет статус "опубликован".
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->getState() === static::STATE_PUBLISHED;
    }

    /**
     * Возвращает значение дневной квоты.
     *
     * @return int|string
     */
    public function getDayQuote()
    {
        return $this->getContentValue('day_quote', null);
    }

    /**
     * Возвращает значение месячной квоты.
     *
     * @return int|string
     */
    public function getMonthQuote()
    {
        return $this->getContentValue('month_quote', null);
    }

    /**
     * Возвращает значение общей квоты.
     *
     * @return int|string
     */
    public function getTotalQuote()
    {
        return $this->getContentValue('total_quote', null);
    }

    /**
     * Возвращает имена источников, используемых в данном типе отчета.
     *
     * @return string[]|array|null
     */
    public function getSourcesNamesList()
    {
        return \is_array($value = $this->getContentValue('content.sources', null))
            ? \array_unique(array_filter($value))
            : null;
    }

    /**
     * Возвращает имена филдов данных, используемых в данном типе отчета.
     *
     * @return string[]|array|null
     */
    public function getFieldsList()
    {
        return \is_array($value = $this->getContentValue('content.fields', null))
            ? \array_unique(array_filter($value))
            : null;
    }
}
