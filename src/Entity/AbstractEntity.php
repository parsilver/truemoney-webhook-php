<?php

namespace Farzai\TruemoneyWebhook\Entity;

use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable
{
    /**
     * Raw data.
     *
     * @var array|null
     */
    protected $data;

    /**
     * Entity data
     */
    final public function __construct(array $data = [])
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
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this->data[$name] ?? null;
    }

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
