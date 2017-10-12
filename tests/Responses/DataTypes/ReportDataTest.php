<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportSource;

/**
 * Class ReportDataTest.
 */
class ReportDataTest extends AbstractDataTypeTestCase
{
    /**
     * @var ReportData
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = ReportData::class;

    /**
     * Тестируем акцессоры данных.
     */
    public function testDataAccessors()
    {
        $this->assertEquals('domain', $this->instance->getDomainUid());
        $this->assertEquals('domain_full_report@domain', $this->instance->getReportTypeUid());
        $this->assertEquals('М111ОУ96', $this->instance->getVehicleId());
        $this->assertEquals('GRZ', $this->instance->getQueryType());
        $this->assertEquals('М111ОУ96', $this->instance->getQueryBody());
        $this->assertEquals(11, $this->instance->getSuccessSourcesCount());
        $this->assertEquals(1, $this->instance->getProgressSourcesCount());
        $this->assertEquals(5, $this->instance->getErrorsSourcesCount());
        $this->assertEquals(17, $this->instance->getTotalSourcesCount());
        $this->assertEquals(16, $this->instance->getSourcesCountCompleted());
        $this->assertFalse($this->instance->generationIsCompleted());
        $this->assertFalse($this->instance->generationIsFailed());
        $this->assertTrue($this->instance->generationIsInProgress());
        $this->assertEquals('report_type_uid@domain', $this->instance->getUid());
        $this->assertEquals('NONAME', $this->instance->getName());
        $this->assertEquals('Some comment', $this->instance->getComment());
        $this->assertEquals(Carbon::parse('2017-08-09T14:50:41.288Z'), $this->instance->getCreatedAt());
        $this->assertEquals('system', $this->instance->getCreatedBy());
        $this->assertEquals(Carbon::parse('2017-08-09T14:53:07.350Z'), $this->instance->getUpdatedAt());
        $this->assertEquals('system', $this->instance->getUpdatedBy());
        $this->assertEquals(Carbon::parse('1900-01-01T00:00:00.000Z'), $this->instance->getActiveFrom());
        $this->assertEquals(Carbon::parse('3000-01-01T00:00:00.000Z'), $this->instance->getActiveTo());

        $this->assertIsArray($sources = $this->instance->sources());
        foreach ($sources as $source) {
            $this->assertInstanceOf(ReportSource::class, $source);
        }

        $this->assertIsArray($content = $this->instance->getContent());
        foreach (['identifiers', 'tech_data', 'additional_info'] as $key) {
            $this->assertArrayHasKey($key, $content);
        }

        $this->assertEquals(
            'XW7BK40K90S000118',
            $this->instance->getField('identifiers.vehicle.vin')
        );
        $this->assertEquals(
            [
                'vin'     => 'XW7BK40K90S000118',
                'reg_num' => 'В061ВК196',
                'sts'     => '66СУ629581',
                'pts'     => '78ММ740718',
            ],
            $this->instance->getField('identifiers.vehicle')
        );
        $this->assertEquals(
            'XW7BK40K90S000118',
            $this->instance->getField('identifiers.vehicle.vin')
        );
        $this->assertEquals(
            'Тойота',
            $this->instance->getField('tech_data.model.name.original')
        );
        $this->assertEquals(
            203.6,
            $this->instance->getField('tech_data.engine.power.kw')
        );

        $this->assertEquals(null, $this->instance->getField('identifiers.vehicle.vin123123'));
    }

    /**
     * Тестируем объекты-хранители информации по источникам.
     */
    public function testReportSources()
    {
        $sources_names = $this->instance->getSourcesNames();
        $need_names    = ['base', 'tech.base', 'strafe', 'ramiosago.ext', 'calc.nalog', 'calc.osago', 'pledge',
            'base.tech', 'base.ext', 'gibdd.dtp', 'carprice', 'base.taxi', 'tech.ext', 'gibdd.history',
            'ramiosago.base', 'gibdd.restrict', 'gibdd.wanted', ];

        foreach ($need_names as $name) {
            $this->assertTrue(in_array($name, $sources_names));
        }

        $source = $this->instance->getSourceByName('base');
        $this->assertTrue($source->isSuccess());
        $this->assertFalse($source->isError());
        $this->assertFalse($source->isProgress());

        $source = $this->instance->getSourceByName('tech.base');
        $this->assertTrue($source->isError());
        $this->assertFalse($source->isSuccess());
        $this->assertFalse($source->isProgress());

        $source = $this->instance->getSourceByName('strafe');
        $this->assertTrue($source->isProgress());
        $this->assertFalse($source->isError());
        $this->assertFalse($source->isSuccess());

        $this->assertNull($this->instance->getSourceByName('bla bla bla'));

        foreach (['base', 'pledge'] as $source_name) {
            $this->instance->getSourceByName($source_name)->isSuccess();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getInstanceContent()
    {
        $data = json_decode(
            file_get_contents(__DIR__ . '/../../raw_data/report_content.json'),
            true
        );

        return $data['data'][0];
    }
}
