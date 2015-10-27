<?php

// There are have been some major API changes in Guzzle over the years, we are focusing on Guzzle 6
// http://docs.guzzlephp.org/en/latest/overview.html
// Supports Curl or plan PHP

// To Install
// composer require guzzlehttp/guzzle:~6.0

require_once __DIR__.'/vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client([
    'base_uri' => 'https://api.spotify.com/v1/',
    'timeout' => 1.5
]);

$response = $client->get('search?q=test&type=track');
// use the response
$data = json_decode($response->getBody());
if ($data)
{
    echo $data->tracks->items[0]->id." ".$data->tracks->items[0]->name."\n";
}


// Thats pretty reasonable code, lets look a little bit more at errors
$client = new Client([
    'base_uri' => 'https://api.spotify.com/v1/',
    'timeout' => 0.001
]);
try
{
    $response = $client->get('search?q=test&type=track');
}
catch(Exception $e)
{
    echo get_class($e)."\n";
    echo $e->getMessage()."\n";
}

// Exceptions aren't so bad, let get some other status codes
$client = new Client([
    'base_uri' => 'http://httpstat.us/',
    'timeout' => 2,
]);
try
{
    $response = $client->get('404');
}
catch(Exception $e)
{
    echo get_class($e)."\n";
    echo $e->getMessage()."\n";
}

try
{
    $response = $client->get('500');
}
catch(Exception $e)
{
    echo get_class($e)."\n";
    echo $e->getMessage()."\n";
}

try
{
    $response = $client->get('400');
}
catch(Exception $e)
{
    echo get_class($e)."\n";
    echo $e->getMessage()."\n";
}

// Hey look a helpful exception hierarchy, not too shabby
//
// So I write a site using guzzle, handle all my errors, use short timeouts
// My site gets moderately busy and it goes down
// https://developer.spotify.com/web-api/user-guide/#rate-limiting
// Oh yeah i'm rate limited, or maybe it was just too slow waiting around for all those api calls
// and I ran out of php-fpm workers
//
// Ok so a real life app likely needs caching, and maybe we shouldn't even use guzzle
// we could cache data in redis etc, and use a work queue to refresh it
//
// We aren't going to go down that path today, instead we are going to take a small detour
// into microservices and then look at how we can make multiple http requests at once
//
// 3-microservices.php
