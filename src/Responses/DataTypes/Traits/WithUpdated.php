<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

use DateTime;
use Carbon\Carbon;

/**
 * Trait WithUpdated.
 */
trait WithUpdated
{
    /**
     * Возвращает дату/время, когда был обновлен.
     *
     * @return Carbon|null
     */
    public function getUpdatedAt()
    {
        return ! empty($value = $this->getContentValue('updated_at', null))
            ? $this->convertToCarbon($value)
            : null;
    }

    /**
     * Возвращает идентификатор пользователя, которым был обновлен.
     *
     * @return null|string
     */
    public function getUpdatedBy()
    {
        return $this->getContentValue('updated_by', null);
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
