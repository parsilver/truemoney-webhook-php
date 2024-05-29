<?php

namespace Farzai\TruemoneyWebhook\Contracts;

interface MessageParser
{
    /**
     * Parse the request from Truemoney webhook.
     *
     * @return mixed
     */
    public function parse(array $data);
}
