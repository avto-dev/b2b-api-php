<?php

namespace AvtoDev\B2BApi\Tests\Tokens;

use AvtoDev\B2BApi\Tokens\AbstractToken;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;

class AbstractTokenTest extends AbstractUnitTestCase
{
    /**
     * @var AbstractTokenMock
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->token = new AbstractTokenMock('some', 'test');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->token);

        parent::tearDown();
    }

    /**
     * Тест конструктора.
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertEquals('sometest', $this->token->getToken());
        $this->assertFalse(false, $this->token->parse(false));
        $this->assertIsArray($this->token->parse('some'));

        $this->assertEquals('aaabbb', $this->token->refresh('aaa', 'bbb')->getToken());

        // Убеждаемся, что без передачи аргументов объект тоже создается
        $this->assertInstanceOf(AbstractToken::class, new AbstractTokenMock);
    }
}
