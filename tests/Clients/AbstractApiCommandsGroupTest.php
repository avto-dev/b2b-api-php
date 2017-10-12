<?php

namespace AvtoDev\B2BApi\Tests\Clients;

use Carbon\Carbon;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Clients\AbstractApiCommandsGroup;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractClientMock;
use AvtoDev\B2BApi\Tests\Clients\Mocks\AbstractApiCommandsGroupMock;

/**
 * Class AbstractApiCommandsGroupTest.
 */
class AbstractApiCommandsGroupTest extends AbstractUnitTestCase
{
    /**
     * @var AbstractApiCommandsGroupMock
     */
    protected $group;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->group = new AbstractApiCommandsGroupMock(new AbstractClientMock);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->group);

        parent::tearDown();
    }

    /**
     * Тест метода `convertToCarbon()`.
     *
     * @see AbstractApiCommandsGroup::convertToCarbon()
     */
    public function testToCarbon()
    {
        $dt         = new \DateTime;
        $test_stamp = 1483634723;

        foreach ([
                     Carbon::createFromTimestamp($test_stamp),
                     $dt->setTimestamp($test_stamp),
                     $test_stamp,
                     '2000-12-12 12:12:12',
                 ] as $item) {
            $this->assertInstanceOf(Carbon::class, $this->group->publicToCarbon($item));
        }

        $this->assertNull($this->group->publicToCarbon(new \stdClass));
    }
}
