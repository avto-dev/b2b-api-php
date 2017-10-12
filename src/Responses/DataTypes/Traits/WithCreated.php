<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

use DateTime;
use Carbon\Carbon;

/**
 * Trait WithCreated.
 */
trait WithCreated
{
    /**
     * Возвращает дату/время, когда был создан.
     *
     * @return Carbon|null
     */
    public function getCreatedAt()
    {
        return ! empty($value = $this->getContentValue('created_at', null))
            ? $this->convertToCarbon($value)
            : null;
    }

    /**
     * Возвращает идентификатор пользователя, которым был создан.
     *
     * @return null|string
     */
    public function getCreatedBy()
    {
        return $this->getContentValue('created_by', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);

    /**
     * Преобразует полученное значение в объект Carbon.
     *
     * @param Carbon|DateTime|int|string $value
     *
     * @return null|Carbon
     */
    abstract protected function convertToCarbon($value);
}
