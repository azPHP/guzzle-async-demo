<?php

// You need to talk to an http service, so you start in the simplest way possible

$url = "https://api.spotify.com/v1/search?q=test&type=track";

$raw = file_get_contents($url);
$data = json_decode($raw);
if ($data)
{
    // do something here
    echo $data->tracks->items[0]->id." ".$data->tracks->items[0]->name."\n";
}


// Just 2 lines of code, PHP is the best
// but a week later you site is down, because the spotify api is slow

// OK maybe that code is doing it wrong, lets be a bit better

// http://us2.php.net/manual/en/context.http.php
$http_options = [
    'http' => [
        'timeout' => 1.5
    ]
];
$raw = file_get_contents($url, false, stream_context_create($http_options));
$data = json_decode($raw);
if ($data)
{
    // do something here
    echo $data->tracks->items[0]->id." ".$data->tracks->items[0]->name."\n";
}


// Now whenever I hit a timeout or when spotify rate limits me my app breaks, maybe I should do error better error handling
$http_options = [
    'http' => [
        'timeout' => 1.5
    ]
];
$raw = file_get_contents($url, false, stream_context_create($http_options));
$data = json_decode($raw);
if ($data && $http_response_header[0] == "HTTP/1.1 200 OK")
{
    // do something here
    echo $data->tracks->items[0]->id." ".$data->tracks->items[0]->name."\n";
}
else
{
    // handle errors here
    echo "Oh noes something broke\n";
}


// Man, this API sucks I hate PHP
// Ok maybe I just need to find a library to help
// See 2-guzzle-intro.php
