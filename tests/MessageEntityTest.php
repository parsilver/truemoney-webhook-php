<?php

use Farzai\TruemoneyWebhook\Entity\Message;
use Farzai\TruemoneyWebhook\MessageParser;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

it('should parse data from array success', function () {
    $data = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $entity = new Message($data);

    expect($entity->event_type)->toBe('P2P');
    expect($entity->received_time)->toBe('2022-01-31T13:02:23+0700');
    expect($entity->amount)->toBe(100);
    expect($entity->sender_mobile)->toBe('0988882222');
    expect($entity->message)->toBe('ค่าไอเทม');
    expect($entity->lat)->toBe(1653538793);
});

it('should parse data from incoming request success', function () {
    $secretKey = 'secret';

    $payload = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $steam = $this->createMock(StreamInterface::class);
    $steam->method('getContents')->willReturn(json_encode([
        'message' => JWT::encode($payload, $secretKey, 'HS256'),
    ]));

    $server = $this->createMock(ServerRequestInterface::class);
    $server->method('getBody')->willReturn($steam);

    $jsonData = @json_decode($server->getBody()->getContents(), true) ?: [];

    $entity = (new MessageParser([
        'secret' => $secretKey,
    ]))->parse($jsonData);

    expect($entity->event_type)->toBe('P2P');
    expect($entity->received_time)->toBe('2022-01-31T13:02:23+0700');
    expect($entity->amount)->toBe(100);
    expect($entity->sender_mobile)->toBe('0988882222');
    expect($entity->message)->toBe('ค่าไอเทม');
    expect($entity->lat)->toBe(1653538793);

    expect((string) $entity)->toBe(json_encode($payload));

});

it('can encode to json string success', function () {
    $data = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $entity = new Message($data);

    expect($entity->asJson())->toBe(json_encode($data));
});

it('can get and set array data success', function () {
    $data = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $entity = new Message($data);

    expect($entity->asArray())->toBe($data);

    // Set new data
    $entity->event_type = 'P2M';

    expect($entity->event_type)->toBe('P2M');
});
