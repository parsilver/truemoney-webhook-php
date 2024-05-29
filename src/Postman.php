<?php

namespace Farzai\TruemoneyWebhook;

use Farzai\TruemoneyWebhook\Contracts\MessageParser as MessageParserContract;
use Farzai\TruemoneyWebhook\Contracts\ServerRequestFactory as ServerRequestFactoryContract;
use Farzai\TruemoneyWebhook\Entity\Message;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

class Postman
{
    private array $config = [
        // The key used to sign the JWT.
        'secret' => null,

        // Algorithm used to sign the token.
        'alg' => 'HS256',
    ];

    private ServerRequestFactoryContract $serverRequestFactory;

    private MessageParserContract $messageParser;

    /**
     * Postman constructor.
     *
     * @param  array  $config
     *                         Notes: Config are required keys: 'secret',
     *                         Optional keys: 'alg',
     */
    public function __construct(array $config)
    {
        $this->config = array_merge($this->config, $config);
        $this->messageParser = new MessageParser($this->config);

        $this->setServerRequestFactory(new ServerRequestFactory);
    }

    /**
     * Capture request from Truemoney webhook.
     *
     * @throw RuntimeException
     *
     * @return Message
     */
    public function capture(?ServerRequestInterface $request = null)
    {
        $this->validateConfig($this->getConfig());

        if (! $request) {
            $request = $this->serverRequestFactory->create();
        }

        $this->validateRequest($request);

        $jsonData = @json_decode($request->getBody()->getContents(), true) ?: [];

        return $this->messageParser->parse($jsonData);
    }

    public function setServerRequestFactory(ServerRequestFactoryContract $serverRequestFactory)
    {
        $this->serverRequestFactory = $serverRequestFactory;

        return $this;
    }

    public function getServerRequestFactory(): ServerRequestFactoryContract
    {
        return $this->serverRequestFactory;
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    private function validateConfig(array $config)
    {
        // Check secret key is required
        if (! isset($config['secret']) || empty($config['secret'])) {
            throw new InvalidArgumentException('Invalid config. "secret" is required.');
        }
    }

    private function validateRequest(ServerRequestInterface $request)
    {
        if ($request->getMethod() !== 'POST') {
            throw new RuntimeException('Invalid request method.');
        }

        $contentType = $request->getHeader('Content-Type')[0] ?? '';

        if ($contentType !== 'application/json') {
            throw new RuntimeException('Invalid content type.');
        }

        if (empty($request->getBody()->getContents())) {
            throw new RuntimeException('Invalid request body.');
        }
    }
}
