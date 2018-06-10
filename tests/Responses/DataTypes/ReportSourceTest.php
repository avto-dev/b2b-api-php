<?php

namespace AvtoDev\B2BApi\Tests\Responses\DataTypes;

use AvtoDev\B2BApi\Responses\DataTypes\Report\ReportSource;

class ReportSourceTest extends AbstractDataTypeTestCase
{
    /**
     * @var ReportSource
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected $instance_class = ReportSource::class;

    /**
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('ERROR', ReportSource::SOURCE_STATUS_ERROR);
        $this->assertEquals('OK', ReportSource::SOURCE_STATUS_SUCCESS);
        $this->assertEquals('PROGRESS', ReportSource::SOURCE_STATUS_PROGRESS);
    }

    /**
     * @return void
     */
    public function testBasicFeatures()
    {
        $this->assertIsArray($this->instance->getData());
        $this->assertTrue(is_bool($this->instance->isSuccess()));
        $this->assertTrue(is_bool($this->instance->isProgress()));
        $this->assertTrue(is_bool($this->instance->isError()));
        $this->assertIsNotEmptyString($this->instance->getStatus());
    }

    /**
     * Тест методов `toArray()` и `toJson()`.
     *
     * @return void
     */
    public function testToArrayAndToJson()
    {
        $this->assertIsArray($array = $this->instance->toArray());
        $this->assertJson($this->instance->toJson());

        foreach (['name', 'status', 'data'] as $key) {
            $this->assertArrayHasKey($key, $array);
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

        return $data['data'][0]['state']['sources'][0];
    }
}
