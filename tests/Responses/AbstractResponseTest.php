<?php

namespace AvtoDev\B2BApi\Tests\Responses;

use GuzzleHttp\Psr7\Response;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;
use AvtoDev\B2BApi\Tests\Responses\Mocks\ArrayableMock;
use AvtoDev\B2BApi\Tests\Responses\Mocks\AbstractResponseMock;

class AbstractResponseTest extends AbstractUnitTestCase
{
    /**
     * @var AbstractResponseMock
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->response = new AbstractResponseMock;
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
     * Тест метода `toJson()`.
     *
     * @return void
     */
    public function testToJson()
    {
        $this->assertJson($this->response->toJson(0));
    }

    /**
     * Тест методов `setRaw()` и `getRaw()`.
     *
     * @return void
     */
    public function testRawSetterAndGetter()
    {
        $this->assertInstanceOf(AbstractResponseMock::class, $this->response->setRaw(['a' => 3.14]));
        $this->assertEquals(['a' => 3.14], $this->response->getRaw());

        $this->response->setRaw('{"a":"abc"}');
        $this->assertEquals(['a' => 'abc'], $this->response->getRaw());

        $this->response->setRaw(new ArrayableMock(['fff' => 'vvv']));
        $this->assertEquals(['fff' => 'vvv'], $this->response->getRaw());

        $this->response->setRaw(new \stdClass);
        $this->assertNull($this->response->getRaw());

        $this->response->setRaw(null);
        $this->assertNull($this->response->getRaw());

        $this->response->setRaw(new Response(200, [], '{"bbb":"aaa"}'));
        $this->assertEquals(['bbb' => 'aaa'], $this->response->getRaw());

        // Ну и тест вызова из конструктора
        $response = new AbstractResponseMock(new ArrayableMock(['fff' => 'vvv']));
        $this->assertEquals(['fff' => 'vvv'], $response->getRaw());

        $response = new AbstractResponseMock(['fff' => 'yyy']);
        $this->assertEquals(['fff' => 'yyy'], $response->getRaw());
    }

    /**
     * Тест метода `getValue()`.
     *
     * @return void
     */
    public function testGetValue()
    {
        $this->response->setRaw([
            'a' => [
                'b' => 1,
                'c' => 2,
            ],
            'd' => 3,
        ]);

        $this->assertEquals(['b' => 1, 'c' => 2], $this->response->getValue('a'));
        $this->assertEquals(1, $this->response->getValue('a.b'));
        $this->assertEquals(3, $this->response->getValue('d'));

        $this->assertEquals(666, $this->response->getValue('bla bla', 666));
    }
}
