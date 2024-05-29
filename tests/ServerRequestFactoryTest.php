<?php

use Farzai\TruemoneyWebhook\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

it('should create request success', function () {
    // Create a new server request.
    $factory = new ServerRequestFactory();

    $request = $factory->create();

    expect($request)->toBeInstanceOf(ServerRequestInterface::class);

    // Get the request method.
    expect($request->getMethod())->toBe('GET');
});
