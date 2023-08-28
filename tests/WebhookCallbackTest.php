<?php

use Farzai\TruemoneyWebhook\Postman;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

it('should success', function () {
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

    expect($entity->event_type)->toBe('P2P');
    expect($entity->received_time)->toBe('2022-01-31T13:02:23+0700');
    expect($entity->amount)->toBe(100);
    expect($entity->sender_mobile)->toBe('0988882222');
    expect($entity->message)->toBe('ค่าไอเทม');
    expect($entity->lat)->toBe(1653538793);
});

it('should error if invalid config', function () {
    $postman = new Postman([
        'secret' => '',
    ]);
})->throws(InvalidArgumentException::class, 'Invalid config. "secret" is required.');

it('should error if invalid method', function () {
    $mock = $this->createMock(ServerRequestInterface::class);
    $mock->method('getMethod')->willReturn('GET');

    $postman = new Postman([
        'secret' => 'secret',
    ]);

    $postman->capture($mock);
})->throws(RuntimeException::class, 'Invalid request method.');

it('should error if content type is not json', function () {
    $mockBody = $this->createMock(StreamInterface::class);
    $mockBody->method('getContents')->willReturn(json_encode(['message' => 'test']));

    $mock = $this->createMock(ServerRequestInterface::class);
    $mock->method('getMethod')->willReturn('POST');
    $mock->method('getBody')->willReturn($mockBody);
    $mock->method('getHeader')->willReturn(['x-www-form-urlencoded']);

    $postman = new Postman([
        'secret' => 'secret',
    ]);

    $postman->capture($mock);
})->throws(RuntimeException::class, 'Invalid content type.');

it('should error if jwt secret key is invalid', function () {
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

    $postman = new Postman([
        'secret' => 'secret',
    ]);

    $postman->capture($mock);
})->throws(RuntimeException::class, 'Signature verification failed');
