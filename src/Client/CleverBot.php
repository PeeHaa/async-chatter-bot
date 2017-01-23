<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Client;

use Amp\Artax\Client as ArtaxClient;
use Amp\Artax\Request;
use Amp\Promise;
use Amp\Success;
use PeeHaa\AsyncChatterBot\Response\CleverBot as Response;

class CleverBot
{
    const SESSION_SETUP_URL = 'http://www.cleverbot.com';

    const API_URL = 'http://www.cleverbot.com/webservicemin?uc=3210&botapi=https%3A%2F%2Fgithub.com%2Fpeehaa%2Fasync-chatter-bot&bot=c&cbsid=AYGPJ73P5B';

    const BASE_PARAMETERS = [
        'stimulus'   => '',
        'islearning' => 1,
        'icognoid'   => 'wsf',
    ];

    private $client;

    private $isSetUp = false;

    private $previousResponse;

    public function __construct(ArtaxClient $client)
    {
        $this->client = $client;
    }

    public function request(string $text): Promise
    {
        // we need to get a valid session before being able to scrape the bot responses
        if (!$this->isSetUp) {
            return \Amp\pipe($this->setUp(), function() use ($text) {
                $this->isSetUp = true;

                return $this->request($text);
            });
        }

        $request = $this->buildRequest($text);

        return \Amp\resolve(function() use ($request) {
            $this->previousResponse = new Response((yield $this->client->request($request))->getBody());

            return new Success($this->previousResponse);
        });
    }

    private function setUp(): Promise
    {
        $request = (new Request())
            ->setMethod('GET')
            ->setUri(self::SESSION_SETUP_URL)
            ->setAllHeaders([
                'Accept-Language'  => 'en;q=1.0',
            ])
        ;

        return $this->client->request($request);
    }

    private function buildRequest(string $text): Request
    {
        $parameters = self::BASE_PARAMETERS;

        if ($this->previousResponse) {
            $parameters = array_merge($parameters, $this->previousResponse->getParameters());
        }

        $parameters['stimulus']    = $text;
        $parameters['icognocheck'] = $this->generateChecksum($parameters);

        return (new Request())
            ->setMethod('POST')
            ->setUri(self::API_URL)
            ->setAllHeaders([
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ])
            ->setBody(http_build_query($parameters))
        ;
    }

    private function generateChecksum(array $parameters): string
    {
        $data = http_build_query($parameters);

        // https://github.com/pierredavidbelanger/chatter-bot-api/blob/master/php/chatterbotapi.php#L165
        // this removes `stimulus=` and cuts off the query string to be hashed
        // don't ask, just trust it works
        // if for some reason it doesn't work you're on your own
        return md5(substr($data, 9, 26));
    }
}
