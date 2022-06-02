<?php

namespace Farzai\TruemoneyWebhook\Tests;

use Psr\Http\Message\ServerRequestInterface;
use Farzai\TruemoneyWebhook\Postman;
use Firebase\JWT\JWT;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use InvalidArgumentException;

class WebhookCallbackTest extends TestCase
{

    public function test_should_success()
    {
        $expects = [
            'event_type' => 'P2P',
            'received_time' => '2022-01-31T13:02:23+0700',
            'amount' => 100,
            'sender_mobile' => '0988882222',
            'message' => 'ค่าไอเทม',
            'lat' => 1653538793,
        ];

        // Encode jwt
        $jwtEncrypted = JWT::encode($expects, 'secret', 'HS256');

        // Mock incoming request.
        $mockBody = $this->createMock(StreamInterface::class);
        $mockBody->method('getContents')->willReturn(json_encode(['message' => $jwtEncrypted]));

        $mock = $this->createMock(ServerRequestInterface::class);
        $mock->method('getMethod')->willReturn('POST');
        $mock->method('getBody')->willReturn($mockBody);
        $mock->method('getHeader')->willReturn(['application/json']);

        $postman = new Postman([
            'secret' => 'secret',
        ]);

        $entity = $postman->capture($mock);

        $this->assertEquals('P2P', $entity->event_type);
        $this->assertEquals('2022-01-31T13:02:23+0700', $entity->received_time);
        $this->assertEquals(100, $entity->amount);
        $this->assertEquals('0988882222', $entity->sender_mobile);
        $this->assertEquals('ค่าไอเทม', $entity->message);
        $this->assertEquals(1653538793, $entity->lat);
    }


    public function test_should_error_if_invalid_config()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid config. "secret" is required.');

        $postman = new Postman([
            'secret' => '',
        ]);
    }


    public function test_should_error_if_invalid_method()
    {
        $mock = $this->createMock(ServerRequestInterface::class);
        $mock->method('getMethod')->willReturn('GET');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid request method.');

        $postman = new Postman([
            'secret' => 'secret',
        ]);

        $postman->capture($mock);
    }


    public function test_should_error_if_content_type_is_not_json()
    {
        $mockBody = $this->createMock(StreamInterface::class);
        $mockBody->method('getContents')->willReturn(json_encode(['message' => 'test']));

        $mock = $this->createMock(ServerRequestInterface::class);
        $mock->method('getMethod')->willReturn('POST');
        $mock->method('getBody')->willReturn($mockBody);
        $mock->method('getHeader')->willReturn(['x-www-form-urlencoded']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid content type.');

        $postman = new Postman([
            'secret' => 'secret',
        ]);

        $postman->capture($mock);
    }


    public function test_should_error_if_jwt_secret_key_is_invalid()
    {
        $expects = [
            'event_type' => 'P2P',
            'received_time' => '2022-01-31T13:02:23+0700',
            'amount' => 100,
            'sender_mobile' => '0988882222',
            'message' => 'ค่าไอเทม',
            'lat' => 1653538793,
        ];

        // Encode jwt
        $jwtEncrypted = JWT::encode($expects, 'invalid-key', 'HS256');

        // Mock incoming request.
        $mockBody = $this->createMock(StreamInterface::class);
        $mockBody->method('getContents')->willReturn(json_encode(['message' => $jwtEncrypted]));

        $mock = $this->createMock(ServerRequestInterface::class);
        $mock->method('getMethod')->willReturn('POST');
        $mock->method('getBody')->willReturn($mockBody);
        $mock->method('getHeader')->willReturn(['application/json']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Signature verification failed');

        $postman = new Postman([
            'secret' => 'secret',
        ]);

        $postman->capture($mock);
    }
}
