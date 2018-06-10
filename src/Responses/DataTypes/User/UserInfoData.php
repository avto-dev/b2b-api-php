<?php

namespace AvtoDev\B2BApi\Responses\DataTypes\User;

use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUid;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithName;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithTags;
use AvtoDev\B2BApi\Responses\DataTypes\AbstractDataType;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithState;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithActive;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithComment;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithCreated;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithUpdated;
use AvtoDev\B2BApi\Responses\DataTypes\Traits\WithDomainUid;

class UserInfoData extends AbstractDataType
{
    use WithActive, WithCreated, WithUpdated, WithUid, WithComment, WithTags, WithName, WithState, WithDomainUid;

    const USER_STATE_ACTIVE = 'ACTIVE';

    /**
     * Возвращает логин пользователя.
     *
     * @return null|string
     */
    public function getLogin()
    {
        return $this->getContentValue('login', null);
    }

    /**
     * Возвращает true, если пользователь активен.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getState() === static::USER_STATE_ACTIVE;
    }

    /**
     * Возвращает email пользователя.
     *
     * @return null|string
     */
    public function getEmail()
    {
        return $this->getContentValue('email', null);
    }

    /**
     * Возвращает контакты пользователя.
     *
     * @return null|string
     */
    public function getContacts()
    {
        return $this->getContentValue('contacts', null);
    }

    /**
     * Возвращает массив ролей пользователя.
     *
     * @return null|string[]
     */
    public function getRoles()
    {
        return \is_string($value = $this->getContentValue('roles', null))
            ? array_filter(explode(',', (string) $value))
            : null;
    }
}
