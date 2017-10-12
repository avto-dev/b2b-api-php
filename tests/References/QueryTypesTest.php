<?php

namespace AvtoDev\B2BApi\Tests\References;

use AvtoDev\B2BApi\References\QueryTypes;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;

/**
 * Class QueryTypesTest.
 */
class QueryTypesTest extends AbstractUnitTestCase
{
    /**
     * Тест констант справочника.
     *
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('AUTODETECT', QueryTypes::QUERY_TYPE_AUTO);
        $this->assertEquals('VIN', QueryTypes::QUERY_TYPE_VIN);
        $this->assertEquals('GRZ', QueryTypes::QUERY_TYPE_GRZ);
        $this->assertEquals('STS', QueryTypes::QUERY_TYPE_STS);
        $this->assertEquals('PTS', QueryTypes::QUERY_TYPE_PTS);
        $this->assertEquals('CHASSIS', QueryTypes::QUERY_TYPE_CHASSIS);
        $this->assertEquals('BODY', QueryTypes::QUERY_TYPE_BODY);
    }

    /**
     * Тест метода `getAll()`.
     */
    public function testGetAll()
    {
        foreach (['AUTODETECT', 'VIN', 'GRZ', 'STS', 'PTS', 'CHASSIS', 'BODY'] as $item) {
            $this->assertContains($item, QueryTypes::getAll());
        }
    }
}
