<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\Traits;

/**
 * Trait WithComment.
 */
trait WithComment
{
    /**
     * Возвращает комментарий.
     *
     * @return null|string
     */
    public function getComment()
    {
        return $this->getContentValue('comment', null);
    }

    /**
     * @param string     $path
     * @param mixed|null $default
     *
     * @see StackValuesDotAccessible::getStackValueWithDot()
     */
    abstract protected function getContentValue($path, $default = null);
}
