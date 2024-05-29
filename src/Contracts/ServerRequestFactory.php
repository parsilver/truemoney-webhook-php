<?php

namespace Farzai\TruemoneyWebhook\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactory
{
    /**
     * Create a new server request.
     */
    public function create(): ServerRequestInterface;
}
