<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\DataTypes\User\UserInfoData;

/**
 * Class UsersInfoDataTest.
 */
class UsersInfoDataTest extends AbstractDataTypeTestCase
{
    /**
     * @var UserInfoData
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = UserInfoData::class;

    /**
     * Тестируем акцессоры данных.
     */
    public function testDataAccessors()
    {
        $this->assertInstanceOf(UserInfoData::class, $this->instance);
        $this->assertEquals('username@domain', $this->instance->getLogin());
        $this->assertEquals('usermail@gmail.com', $this->instance->getEmail());
        $this->assertEquals('+7 (800) 888 88 88', $this->instance->getContacts());
        $this->assertEquals('ACTIVE', $this->instance->getState());
        $this->assertTrue($this->instance->isActive());
        $this->assertEquals('domain', $this->instance->getDomainUid());
        $this->assertEquals(['ADMIN', 'DOMAIN_ADMIN', 'ALL_REPORTS_READ', 'ALL_REPORTS_WRITE'], $this->instance->getRoles());
        $this->assertEquals('username@domain', $this->instance->getUid());
        $this->assertEquals('Иванов Иван Иванович', $this->instance->getName());
        $this->assertEquals('', $this->instance->getComment());
        $this->assertEquals([], $this->instance->getTags());
        $this->assertEquals(Carbon::parse('2017-06-08T13:05:27.384Z'), $this->instance->getCreatedAt());
        $this->assertEquals('system', $this->instance->getCreatedBy());
        $this->assertEquals(Carbon::parse('2017-08-09T05:29:10.004Z'), $this->instance->getUpdatedAt());
        $this->assertEquals('system', $this->instance->getUpdatedBy());
        $this->assertEquals(Carbon::parse('1900-01-01T00:00:00.000Z'), $this->instance->getActiveFrom());
        $this->assertEquals(Carbon::parse('3000-01-01T00:00:00.000Z'), $this->instance->getActiveTo());
    }

    /**
     * {@inheritdoc}
     */
    protected function getInstanceContent()
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/../../raw_data/user.json'),
            true
        );

        return $data['data'][0];
    }
}
