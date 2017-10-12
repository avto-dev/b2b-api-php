<?php

namespace AvtoDev\B2BApi\Tests\Responses;

use AvtoDev\B2BApi\Responses\DataCollection;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportData;

class DataCollectionTest extends AbstractUnitTestCase
{
    /**
     * @var DataCollection
     */
    protected $collection;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->collection = new DataCollection;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->collection);

        parent::tearDown();
    }

    /**
     * Тест констант класса.
     */
    public function testConstants()
    {
        $this->assertEquals('unknown', DataCollection::DATA_TYPE_UNKNOWN);
        $this->assertEquals('report', DataCollection::DATA_TYPE_REPORT);
    }

    /**
     * Тест методом-акцессоров данных.
     */
    public function testDataAccessors()
    {
        $report_response_json  = file_get_contents(__DIR__ . '/../raw_data/report_content.json');
        $report_response_array = json_decode($report_response_json, true);

        $collection = new DataCollection($report_response_array['data']);
        $this->assertEquals(1, $collection->count());
        $this->assertIsArray($collection->all());
        $this->assertInstanceOf(ReportData::class, $collection->all()[0]);
        $this->assertInstanceOf(ReportData::class, $collection->first());
        $this->assertEquals($collection->all()[0], $collection->first());
        $this->assertInstanceOf(DataCollection::class, $collection->each(function ($data) {
            $this->assertInstanceOf(ReportData::class, $data);
        }));
        $this->assertFalse($collection->isEmpty());

        foreach ($collection as $key => $item) {
            $this->assertInstanceOf(ReportData::class, $item);
            $this->assertNotNull($key);
        }
        $this->assertNotNull($collection->current());
        $this->assertNotNull($collection->next());
    }
}
