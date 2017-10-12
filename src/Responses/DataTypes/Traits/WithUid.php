<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithUid.
 */
trait WithUid
{
    /**
     * Возвращает UID.
     *
     * @return null|string
     */
    public function getUid()
    {
        return $this->getContentValue('uid', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
