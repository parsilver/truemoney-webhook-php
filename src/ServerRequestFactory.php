<?php

namespace Farzai\TruemoneyWebhook;

use Farzai\TruemoneyWebhook\Contracts\ServerRequestFactory as ServerRequestFactoryContract;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory implements ServerRequestFactoryContract
{
    /**
     * Create a new server request.
     */
    public function create(): ServerRequestInterface
    {
        $factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $factory,
            $factory,
            $factory,
            $factory
        );

        return $creator->fromGlobals();
    }
}
