<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportStatusData;

class ReportStatusDataTest extends AbstractDataTypeTestCase
{
    /**
     * @var ReportStatusData
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = ReportStatusData::class;

    /**
     * Тестируем акцессоры данных.
     */
    public function testDataAccessors()
    {
        $this->assertInstanceOf(ReportStatusData::class, $this->instance);
        $this->assertTrue($this->instance->isNew());
        $this->assertEquals('domain_REPORT_UID_WITH_HASH_HERE@domain', $this->instance->getUid());
        $this->assertEquals('domain_ONE_MORE_REPORT_UID_WITH_HASH_HERE@domain', $this->instance->getProcessRequestUid());
        $this->assertEquals(Carbon::parse('2017-08-09T20:37:56.214Z'), $this->instance->getSuggestGet());
    }

    /**
     * {@inheritdoc}
     */
    protected function getInstanceContent()
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/../../raw_data/report_make.json'),
            true
        );

        return $data['data'][0];
    }
}
