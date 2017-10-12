<?php

namespace AvtoDev\B2BApi\Tests\Clients\v1;

use AvtoDev\B2BApi\Clients\v1\Dev\DevCommandsGroup;

/**
 * Class ClientTest.
 */
class ClientTest extends ClientTestCase
{
    /**
     * Тест метода `getDefaultConfig()`.
     *
     * @return void
     */
    public function testGetDefaultConfig()
    {
        $default_config = $this->client->getConfig();

        $keys = [
            'api',
            'client',
            'http_client',
            'is_test',
            'use_api_version',
            'http_client',
            'use_http_client',
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $default_config);
        }

        $this->assertIsString($default_config['use_api_version']);
    }

    /**
     * Тест метода `getClientVersion()`.
     *
     * @return void
     */
    public function testGetClientVersion()
    {
        $this->assertEquals('v2.0', $this->client->getClientVersion());
    }

    /**
     * Тест метода `dev()`.
     *
     * @return void
     */
    public function testDevCommands()
    {
        $this->assertInstanceOf(DevCommandsGroup::class, $this->client->dev());
    }
}
