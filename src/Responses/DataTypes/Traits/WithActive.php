<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

use DateTime;
use Carbon\Carbon;

/**
 * Trait WithActive.
 */
trait WithActive
{
    /**
     * Возвращает дату/время, с которого активен.
     *
     * @return Carbon|null
     */
    public function getActiveFrom()
    {
        return ! empty($value = $this->getContentValue('active_from', null))
            ? $this->convertToCarbon($value)
            : null;
    }

    /**
     * Возвращает дату/время, по которые активен.
     *
     * @return Carbon|null
     */
    public function getActiveTo()
    {
        return ! empty($value = $this->getContentValue('active_to', null))
            ? $this->convertToCarbon($value)
            : null;
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
