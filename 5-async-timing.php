<?php
require __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use GuzzleHttp\HandlerStack;
$stats = function(TransferStats $stats)
{
    echo $stats->getTransferTime()." ".$stats->getEffectiveUri()."\n";
};

// if you are going to use multiple clients, and want to make requests async among all of them
// you have to pass a handler into the client, so they all run in the same loop
$handler = HandlerStack::create();
$usernameClient = new Client([
    'base_uri' => 'http://localhost:8000/',
    'timeout' =>10 ,
    'on_stats' => $stats,
    'handler' => $handler,
]);
$passwordClient = new Client([
    'base_uri' => 'http://localhost:8001/',
    'timeout' => 10,
    'on_stats' => $stats,
    'handler' => $handler,
]);
$emailClient = new Client([
    'base_uri' => 'http://localhost:8002/',
    'timeout' =>10,
    'on_stats' => $stats,
    'handler' => $handler,
]);

$start = microtime(true);
$username1 = $usernameClient->post('generate-username');
$username2 = $usernameClient->post('generate-username');
$password = $passwordClient->post('generate-password');
$email = $emailClient->post('generate-email');
$stop = microtime(true);
$time = $stop-$start;
echo "4 requests in $time seconds\n";

$start = microtime(true);
$promises= [];
$promises['username1'] = $usernameClient->postAsync('generate-username');
$promises['username2'] = $usernameClient->postAsync('generate-username');
$promises['password'] = $passwordClient->postAsync('generate-password');
$promises['email'] = $emailClient->postAsync('generate-email');

$results = GuzzleHttp\Promise\unwrap($promises);
$stop = microtime(true);
$time = $stop-$start;

echo "4 requests in $time seconds\n";
