<?php

namespace Farzai\TruemoneyWebhook\Entity;

use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractEntity implements JsonSerializable
{

    /**
     * Raw data.
     * 
     * @var array|null
     */
    protected $data;

    /**
     * Map data to entity from array.
     * 
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
     * Map data to entity from request.
     * 
     * @param ServerRequestInterface $request
     * @return static
     */
    public static function fromRequest(ServerRequestInterface $request)
    {
        return static::fromArray(@json_decode((string)$request->getBody(), true) ?: []);
    }

    /**
     * Entity data 
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Convert entity to json string.
     * 
     * @return string
     */
    public function asJson()
    {
        return json_encode($this);
    }

    /**
     * Convert entity to array.
     * 
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this->data[$name] ?? null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->asJson();
    }
}
