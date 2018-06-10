<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use AvtoDev\B2BApi\Responses\DataTypes\User\BalanceData;

class UserBalanceDataTest extends AbstractDataTypeTestCase
{
    /**
     * @var BalanceData
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = BalanceData::class;

    /**
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('DAY', BalanceData::BALANCE_TYPE_DAY);
        $this->assertEquals('MONTH', BalanceData::BALANCE_TYPE_MONTH);
        $this->assertEquals('TOTAL', BalanceData::BALANCE_TYPE_TOTAL);
    }

    /**
     * Тестируем акцессоры данных.
     */
    public function testDataAccessors()
    {
        $this->assertInstanceOf(BalanceData::class, $this->instance);
        $this->assertEquals('default_report_type_uid@domain', $this->instance->getReportTypeUid());
        $this->assertEquals('DAY', $this->instance->getBalanceType());
        $this->assertEquals(0, $this->instance->getQuoteInit());
        $this->assertEquals(0, $this->instance->getQuoteUp());
        $this->assertEquals(5, $this->instance->getQuoteUse());

        $this->assertTrue($this->instance->isDailyBalance());
        $this->assertFalse($this->instance->isMonthlyBalance());
        $this->assertFalse($this->instance->isTotalBalance());
    }

    /**
     * {@inheritdoc}
     */
    protected function getInstanceContent()
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/../../raw_data/balance.json'),
            true
        );

        return $data['data'][0];
    }
}
