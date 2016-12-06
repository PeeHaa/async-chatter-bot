<?php declare(strict_types=1);

namespace PeeHaa\AsyncChatterBot\Example;

use Amp\Artax\Client;
use PeeHaa\AsyncChatterBot\Client\CleverBot;

require_once __DIR__ . '/../vendor/autoload.php';

$cleverBot = new CleverBot(new Client());

\Amp\wait($result = $cleverBot->request('test'));

var_dump($result);
