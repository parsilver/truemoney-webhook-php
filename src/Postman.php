<?php

namespace Farzai\TruemoneyWebhook;

use Farzai\TruemoneyWebhook\Entity\Message;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
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

    /**
     * Postman constructor.
     *
     * @param  array  $config
     * Notes: Config are required keys: 'secret',
     * Optional keys: 'alg',
     */
    public function __construct(array $config)
    {
        // Validate config
        // Check secret key is required
        if (! isset($config['secret']) || empty($config['secret'])) {
            throw new InvalidArgumentException('Invalid config. "secret" is required.');
        }

        // Set config
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Capture request from Truemoney webhook.
     *
     * @throw RuntimeException
     *
     * @return Message
     */
    public function capture(ServerRequestInterface $request = null)
    {
        if (! $request) {
            $factory = new Psr17Factory();

            $creator = new ServerRequestCreator(
                $factory,
                $factory,
                $factory,
                $factory
            );

            $request = $creator->fromGlobals();
        }

        if ($request->getMethod() !== 'POST') {
            throw new RuntimeException('Invalid request method.');
        }

        return $this->parseMessageFromRequest($request);
    }

    /**
     * Decode jwt and return message entity.
     *
     *
     * @return Message
     *
     * @throws RuntimeException
     */
    public function parseMessageFromRequest(ServerRequestInterface $request)
    {
        if (count($request->getHeader('Content-Type')) === 0 || $request->getHeader('Content-Type')[0] !== 'application/json') {
            throw new RuntimeException('Invalid content type.');
        }

        if (! $request->getBody()->getContents()) {
            throw new RuntimeException('Invalid request body.');
        }

        $jsonData = @json_decode($request->getBody()->getContents(), true) ?: [];

        return $this->parseMessageFromJsonArray($jsonData);
    }

    /**
     * Parse all input data to message entity.
     *
     *
     * @return Message
     *
     * @throws RuntimeException
     */
    public function parseMessageFromJsonArray(array $jsonData)
    {
        if (! $jsonData || ! $jsonData['message']) {
            throw new RuntimeException('Invalid request body.');
        }

        $data = JWT::decode($jsonData['message'], new Key($this->config['secret'], $this->config['alg']));

        // Convert to array
        $data = (array) $data;

        return Message::fromArray($data);
    }
}
