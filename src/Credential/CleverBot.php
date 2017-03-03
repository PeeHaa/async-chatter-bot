<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Credential;

class CleverBot
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
