<?php

namespace AvtoDev\B2BApi\Tests\Responses;

use Carbon\Carbon;
use AvtoDev\B2BApi\Responses\B2BResponse;
use AvtoDev\B2BApi\Responses\DataCollection;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;

class B2BResponseTest extends AbstractUnitTestCase
{
    /**
     * @var B2BResponse
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->response = new B2BResponse([
            'state' => 'ok',
            'size'  => 1,
            'stamp' => '2017-08-09T09:56:01.068Z',
            'data'  => [
                [
                    'some' => 'thing',
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->response);

        parent::tearDown();
    }

    /**
     * Тест метода `convertToArray()`.
     *
     * @return void
     */
    public function testToArray()
    {
        $this->assertIsArray($this->response->toArray());
    }

    /**
     * Тест констант класса.
     */
    public function testConstants()
    {
        $this->assertEquals('ok', B2BResponse::RESPONSE_STATE_SUCCESS);
        $this->assertEquals('fail', B2BResponse::RESPONSE_STATE_FAILED);
    }

    /**
     * Тест метода `getRequestDuration()`.
     *
     * @return void
     */
    public function testGetRequestDuration()
    {
        $this->assertTrue(is_float($this->response->getRequestDuration()));
    }

    /**
     * Тест метода `getState()`.
     *
     * @return void
     */
    public function testGetResponseState()
    {
        $this->assertIsString($this->response->getState());

        $this->response->setRaw(['state' => null]);
        $this->assertNull($this->response->getState());

        $this->response->setRaw(['state' => '']);
        $this->assertNull($this->response->getState());
    }

    /**
     * Тест метода `isSuccess()`.
     *
     * @return void
     */
    public function testResponseStateIsOk()
    {
        $this->assertTrue($this->response->isSuccess());
        $this->assertFalse($this->response->isFailed());

        $this->response->setRaw(['state' => 'blabla']);
        $this->assertFalse($this->response->isSuccess());
        $this->assertTrue($this->response->isFailed());

        $this->response->setRaw(['state' => null]);
        $this->assertFalse($this->response->isSuccess());
        $this->assertTrue($this->response->isFailed());

        $this->response->setRaw(['state' => '']);
        $this->assertFalse($this->response->isSuccess());
        $this->assertTrue($this->response->isFailed());
    }

    /**
     * Тест метода `getRespondedAt()`.
     *
     * @return void
     */
    public function testGetRespondedAt()
    {
        $this->assertInstanceOf(Carbon::class, $this->response->getRespondedAt());

        $this->response->setRaw(['data' => [
            'stamp' => new \stdClass,
        ]]);
        $this->assertNull($this->response->getRespondedAt());
    }

    /**
     * Тест метода `count()`.
     *
     * @return void
     */
    public function testGetResponseSize()
    {
        $this->assertEquals(1, $this->response->count());

        $this->response->setRaw(['data' => [
            [1], [1],
        ]]);
        $this->assertEquals(2, $this->response->count());
    }

    /**
     * Тест метода `data()`.
     */
    public function testData()
    {
        $this->assertInstanceOf(DataCollection::class, $this->response->data());
    }
}
