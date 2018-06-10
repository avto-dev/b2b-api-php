<?php

namespace AvtoDev\B2BApi\Tests\Clients;

use AvtoDev\B2BApi\Clients\ClientInterface;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;

abstract class AbstractClientTestCase extends AbstractUnitTestCase
{
    /**
     * @var AbstractClientMock
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = new AbstractClientMock([
            'is_test' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->client);

        parent::tearDown();
    }

    /**
     * Тест конструктора.
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->client);
    }
}
