<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Client;

use Amp\Artax\Client as ArtaxClient;
use Amp\Artax\Request;
use Amp\Promise;

class CleverBot
{
    const BASE_URL = 'http://www.cleverbot.com';

    const SERVICE_URL = 'http://www.cleverbot.com/webservicemin?uc=321';

    private $client;

    private $isSetUp = false;

    public function __construct(ArtaxClient $client)
    {
        $this->client = $client;
    }

    public function request(string $text): Promise
    {
        if (!$this->isSetUp) {
            $foo = yield $this->setUp();

            var_dump($foo);
            die;
        }

        $request = (new Request())
            ->setMethod('POST')
            ->setUri(self::SERVICE_URL)
            ->setAllHeaders([
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ])
            ->setBody(http_build_query([
                'stimulus' => $text,
                'icognocheck' => md5($text),
            ]))
        ;

        return $this->client->request($request);
    }

    private function setUp()
    {
        $queryString = http_build_query([
            'stimulus'   => '',
            'islearning' => 1,
            'icognoid'   => 'wsf',
        ]);

        return (new Request())
            ->setMethod('GET')
            ->setUri(self::BASE_URL . '?' . $queryString)
            ->setAllHeaders([
                'Accept-Language'  => 'en;q=1.0',
            ])
         ;
    }
}
