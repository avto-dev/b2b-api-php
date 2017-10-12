<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithName.
 */
trait WithName
{
    /**
     * Возвращает имя.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->getContentValue('name', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
