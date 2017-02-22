<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Client;

use Amp\Artax\Client as ArtaxClient;
use Amp\Promise;
use Amp\Success;
use PeeHaa\AsyncChatterBot\Credential\CleverBot as Credentials;
use PeeHaa\AsyncChatterBot\Response\CleverBot as Response;

class CleverBot
{
    const ENDPOINT = 'https://www.cleverbot.com/getreply';

    private $credentials;

    private $client;

    private $previousResponse;

    public function __construct(Credentials $credentials, ArtaxClient $client)
    {
        $this->credentials = $credentials;
        $this->client      = $client;
    }

    public function request(string $text): Promise
    {
        return \Amp\resolve(function() use ($text) {
            $this->previousResponse = new Response((yield $this->client->request($this->buildUrl($text)))->getBody());

            return new Success($this->previousResponse);
        });
    }

    private function buildUrl(string $text): string
    {
        $url = sprintf(self::ENDPOINT . '?input=%s&key=%s', rawurlencode($text), $this->credentials->getKey());

        if ($this->previousResponse) {
            $url .= '&cs=' . rawurlencode($this->previousResponse->getConversationState());
        }

        return $url;
    }
}
