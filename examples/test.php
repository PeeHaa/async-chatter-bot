<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Example;

use Amp\Artax\Client;
use PeeHaa\AsyncChatterBot\Client\CleverBot;

require_once __DIR__ . '/../vendor/autoload.php';

$cleverBot = new CleverBot(new Client());

$promise = $cleverBot->request('Are you ok?');

$response = \Amp\wait($promise);

var_dump($response->getText());

$promise = $cleverBot->request('Are you a bot?');

$response = \Amp\wait($promise);

var_dump($response->getText());

$promise = $cleverBot->request('Are you sane?');

$response = \Amp\wait($promise);

var_dump($response->getText());

$promise = $cleverBot->request('Thanks!');

$response = \Amp\wait($promise);

var_dump($response->getText());

$promise = $cleverBot->request('Good bye now!');

$response = \Amp\wait($promise);

var_dump($response->getText());
