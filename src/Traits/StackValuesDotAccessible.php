<?php

namespace AvtoDev\B2BApi\Traits;

/**
 * Trait StackValuesDotAccessible.
 */
trait StackValuesDotAccessible
{
    /**
     * Возвращает значение из стека данных, обращаясь к нему с помощью dot-нотации.
     *
     * @param string     $path
     * @param mixed|null $default
     *
     * @return array|mixed|null
     */
    public function getStackValueWithDot($path, $default = null)
    {
        if (($current = $this->getAccessorStack()) && is_array($current)) {
            $p = strtok((string) $path, '.');

            while ($p !== false) {
                if (! isset($current[$p])) {
                    return $default;
                }
                $current = $current[$p];
                $p       = strtok('.');
            }
        }

        return $current;
    }

    /**
     * Возвращает стек данных, к которому необходимо обеспечить доступ с помощью dot-нотации.
     *
     * @return array
     */
    abstract protected function getAccessorStack();
}
