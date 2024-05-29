<?php

namespace Farzai\TruemoneyWebhook;

use Farzai\TruemoneyWebhook\Contracts\MessageParser as MessageParserContract;
use Farzai\TruemoneyWebhook\Entity\Message;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;

class MessageParser implements MessageParserContract
{
    private array $config = [
        // The key used to sign
        'secret' => null,

        // Algorithm used to sign the token
        'alg' => 'HS256',
    ];

    /**
     * Parser constructor.
     */
    public function __construct(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Parse the request from Truemoney webhook.
     *
     * @return mixed
     */
    public function parse(array $data)
    {
        if (! $data || ! $data['message']) {
            throw new RuntimeException('Invalid request body.');
        }

        $data = JWT::decode($data['message'], new Key($this->config['secret'], $this->config['alg']));

        return new Message((array) $data);
    }
}
