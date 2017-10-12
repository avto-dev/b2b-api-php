<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithState.
 */
trait WithState
{
    /**
     * Возвращает значение статуса.
     *
     * @return null|string
     */
    public function getState()
    {
        return $this->getContentValue('state', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
