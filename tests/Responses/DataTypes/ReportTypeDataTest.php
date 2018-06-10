<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\DataTypes\ReportType\ReportTypeData;

class ReportTypeDataTest extends AbstractDataTypeTestCase
{
    /**
     * @var ReportTypeData
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = ReportTypeData::class;

    /**
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('PUBLISHED', ReportTypeData::STATE_PUBLISHED);
    }

    /**
     * Тестируем акцессоры данных.
     */
    public function testDataAccessors()
    {
        $this->assertInstanceOf(ReportTypeData::class, $this->instance);
        $this->assertEquals('PUBLISHED', $this->instance->getState());
        $this->assertTrue($this->instance->isPublished());
        $this->assertEquals(0, $this->instance->getDayQuote());
        $this->assertEquals(0, $this->instance->getMonthQuote());
        $this->assertEquals(0, $this->instance->getTotalQuote());
        foreach (['base', 'base.ext', 'gibdd.history', 'gibdd.dtp', 'gibdd.restrict', 'gibdd.wanted'] as $need) {
            $this->assertContains($need, $this->instance->getSourcesNamesList());
        }
        $this->assertIsArray($this->instance->getFieldsList());
        $this->assertEquals('domain', $this->instance->getDomainUid());
        $this->assertEquals('report_uuid_1@domain', $this->instance->getUid());
        $this->assertEquals('Полный отчет', $this->instance->getName());
        $this->assertEquals('', $this->instance->getComment());
        $this->assertIsArray($this->instance->getTags());
        $this->assertEquals(Carbon::parse('2017-07-07T06:12:29.820Z'), $this->instance->getCreatedAt());
        $this->assertEquals('system', $this->instance->getCreatedBy());
        $this->assertEquals(Carbon::parse('2017-08-09T05:29:09.992Z'), $this->instance->getUpdatedAt());
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
            file_get_contents(__DIR__ . '/../../raw_data/report_types.json'),
            true
        );

        return $data['data'][0];
    }
}
