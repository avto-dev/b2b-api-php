<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithDomainUid.
 */
trait WithDomainUid
{
    /**
     * Возвращает UID домена.
     *
     * @return null|string
     */
    public function getDomainUid()
    {
        return $this->getContentValue('domain_uid', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
