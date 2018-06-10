<?php

namespace AvtoDev\B2BApi\Tests\Clients\Mocks;

use AvtoDev\B2BApi\Clients\AbstractClient;

class AbstractClientMock extends AbstractClient
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getClientVersion()
    {
        return '0.0.1-dev';
    }
}
