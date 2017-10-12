<?php

namespace AvtoDev\B2BApi\Tests\Tokens;

use AvtoDev\B2BApi\Tokens\AuthToken;
use AvtoDev\B2BApi\Tests\AbstractUnitTestCase;

/**
 * Class AuthTokenTest.
 *
 * @group tokens
 */
class AuthTokenTest extends AbstractUnitTestCase
{
    /**
     * @var AuthToken
     */
    protected $token;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->token = new AuthToken;
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
     * Тест метода `generate()`.
     *
     * @return void
     */
    public function testGenerate()
    {
        // Цикл нужен для проверки работы стека
        foreach (range(1, 3) as $i) {
            $this->assertEquals(
                $this->token->generate(
                    $username = 'test',
                    $password = '123',
                    $domain = 'test',
                    $age = 5,
                    $timestamp = 1483634723
                ),
                'AR-REST dGVzdEB0ZXN0OjE0ODM2MzQ3MjM6NTpaVGZBTzQramFDdmhWMCs2elk1dWFnPT0='
            );
        }

        // Тест метода __toString
        $token = new AuthToken('test', '123');
        $this->assertIsNotEmptyString($token->getToken());
        $this->assertEquals($token->getToken(), (string) $token);

        // Убеждаемся, что метод работает даже при недостающих аргументах
        $this->assertIsNotEmptyString($this->token->generate('test', '123', 'test', 5));
        $this->assertIsNotEmptyString($this->token->generate('test', '123', 'test'));
        $this->assertIsNotEmptyString($this->token->generate('test', '123'));
    }

    /**
     * Тест метода `parse()`.
     *
     * @return void
     */
    public function testParse()
    {
        $tokens = [
            'AR-REST dGVzdEB0ZXN0OjE0ODM2MzQ3MjM6NTpaVGZBTzQramFDdmhWMCs2elk1dWFnPT0=',
            'dGVzdEB0ZXN0OjE0ODM2MzQ3MjM6NTpaVGZBTzQramFDdmhWMCs2elk1dWFnPT0=',
        ];

        foreach ($tokens as $token) {
            $info = $this->token->parse($token);
            $this->assertEquals('test@test', $info['username']);
            $this->assertEquals(1483634723, $info['timestamp']);
            $this->assertEquals(5, $info['age']);
            $this->assertEquals('ZTfAO4+jaCvhV0+6zY5uag==', $info['salted_hash']);
        }

        foreach ([null, '', new \stdClass, 'asdadad', 'DFSDF fsdfsf'] as $invalid_token) {
            $this->assertFalse($this->token->parse($invalid_token));
        }
    }

    /**
     * Тест метода `extractDomainFromToken()`.
     *
     * @return void
     */
    public function testExtractDomainFromToken()
    {
        $token = $this->token->generate('username', 'password', 'domain1');

        $this->assertEquals('domain1', $this->token->extractDomainFromToken($token));
        $this->assertNull($this->token->extractDomainFromToken('bla bla bla'));

        $token = $this->token->generate('username', 'password');
        $this->assertNull($this->token->extractDomainFromToken($token));
    }

    /**
     * Тест метода `extractUsernameFromToken()`.
     *
     * @return void
     */
    public function testExtractUsernameFromToken()
    {
        $token = $this->token->generate('some_username', 'password', 'domain');
        $this->assertEquals('some_username', $this->token->extractUsernameFromToken($token));

        $token = $this->token->generate('some_username2', 'password');
        $this->assertEquals('some_username2', $this->token->extractUsernameFromToken($token));

        $this->assertNull($this->token->extractUsernameFromToken('bla bla bla'));
    }
}
