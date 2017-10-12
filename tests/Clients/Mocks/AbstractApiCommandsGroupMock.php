<?php

namespace AvtoDev\B2BApi\Tests\Clients\Mocks;

use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;

class AbstractApiCommandsGroupMock extends AbstractApiCommandsGroup
{
    /**
     * Получаем доступ к protected-методу `convertToCarbon()`.
     *
     * @param $value
     *
     * @return \Carbon\Carbon|null
     */
    public function publicToCarbon($value)
    {
        return $this->convertToCarbon($value);
    }
}
