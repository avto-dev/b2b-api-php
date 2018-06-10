<?php

namespace AvtoDev\B2BApi\Tests\Tokens;

use AvtoDev\B2BApi\Tokens\AbstractToken;

class AbstractTokenMock extends AbstractToken
{
    /**
     * {@inheritdoc}
     */
    public static function parse($auth_token)
    {
        return ($auth_token === false)
            ? false
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public static function generate($username, $password)
    {
        return $username . $password;
    }
}
