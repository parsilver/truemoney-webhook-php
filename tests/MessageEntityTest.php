<?php

use Farzai\TruemoneyWebhook\Entity\Message;
use Psr\Http\Message\ServerRequestInterface;

it('should parse data from array success', function () {
    $data = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $entity = Message::fromArray($data);

    expect($entity->event_type)->toBe('P2P');
    expect($entity->received_time)->toBe('2022-01-31T13:02:23+0700');
    expect($entity->amount)->toBe(100);
    expect($entity->sender_mobile)->toBe('0988882222');
    expect($entity->message)->toBe('ค่าไอเทม');
    expect($entity->lat)->toBe(1653538793);
});

it('should parse data from incoming request success', function () {
    $server = $this->createMock(ServerRequestInterface::class);
    $server->method('getBody')->willReturn(json_encode([
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ]));

    $entity = Message::fromRequest($server);

    expect($entity->event_type)->toBe('P2P');
    expect($entity->received_time)->toBe('2022-01-31T13:02:23+0700');
    expect($entity->amount)->toBe(100);
    expect($entity->sender_mobile)->toBe('0988882222');
    expect($entity->message)->toBe('ค่าไอเทม');
    expect($entity->lat)->toBe(1653538793);
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

    $entity = Message::fromArray($data);

    expect($entity->asJson())->toBe(json_encode($data));
});

it('can get array data success', function () {
    $data = [
        'event_type' => 'P2P',
        'received_time' => '2022-01-31T13:02:23+0700',
        'amount' => 100,
        'sender_mobile' => '0988882222',
        'message' => 'ค่าไอเทม',
        'lat' => 1653538793,
    ];

    $entity = Message::fromArray($data);

    expect($entity->asArray())->toBe($data);
});