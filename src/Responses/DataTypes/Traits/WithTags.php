<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithTags.
 */
trait WithTags
{
    /**
     * Возвращает массив тегов.
     *
     * @return null|string[]
     */
    public function getTags()
    {
        return is_string($value = $this->getContentValue('tags', null))
            ? array_unique(array_filter(explode(',', $value)))
            : null;
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
