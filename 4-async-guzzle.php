<?php
// There are 2 ways to do async requests in guzzle
// One is too push requests into a pool, and have a fix # of workers processes those requests
// This is great if you want a super efficient job queue for making api requests
// One procesces can handle a hunge # of requests, while keeping you from overloading
// and upstream server

// The other option is using promises.  Promises are perfect to build your app one.

require_once __DIR__.'/vendor/autoload.php';
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
$usernameClient = new Client([
    'base_uri' => 'http://localhost:8000/',
    'timeout' => 5
]);

$usernamePromise = $usernameClient->postAsync('generate-username');

$usernamePromise->then(
    function(ResponseInterface $response)
    {
        $data = json_decode($response->getBody());
        var_dump($data);
    },
    function (RequestException $e)
    {
        echo "Failed to generate a username\n";
        echo $e->getMessage()."\n";
    }
);

$usernamePromise->wait();

// Ok so that wasn't useful, but lets hit all 3 apis
use GuzzleHttp\Promise\Promise;
$promises= [];
$usernameClient = new Client([
    'base_uri' => 'http://localhost:8000/',
    'timeout' =>10 
]);
$passwordClient = new Client([
    'base_uri' => 'http://localhost:8001/',
    'timeout' => 10
]);
$emailClient = new Client([
    'base_uri' => 'http://localhost:8002/',
    'timeout' =>10 
]);

$promises['username1'] = $usernameClient->postAsync('generate-username');
$promises['username2'] = $usernameClient->postAsync('generate-username');
$promises['password'] = $passwordClient->postAsync('generate-password');
$promises['email'] = $emailClient->postAsync('generate-email');

$results = GuzzleHttp\Promise\unwrap($promises);

foreach($results as $k => $result)
{
    echo $k ." ".$result->getBody()."\n";
}

// So in your app you build of an array of async requests at the top, wait from them all to complete and build the result
// This isn't the only model, but it is the most straight forward, since it lets you thing about things
// in a linear way

// You can also push the promises throughout the code, and then just wait on them when you need the response
// if you use promises to there full extent you can even handle the generic api response handling to
// make a fully async api
$usernamePromise = $usernameClient->postAsync('generate-username');
$username = new Promise(function () use ($usernamePromise, &$username) {
    $data = json_decode($usernamePromise->wait()->getBody());
    $username->resolve($data->username);
});

echo "Username: {$username->wait()}\n";

// if you wanted to pass things all the way into a template you could do a trick like
use GuzzleHttp\Promise\PromiseInterface;
class StringWaiter
{
    private $promise;
    public function __construct(PromiseInterface $promise)
    {
        $this->promise = $promise;
    }

    public function __toString()
    {
        try
        {
            return $this->promise->wait();
        }
        catch(Exception $e)
        {
            return "Error: ".$e->getMessage()."\n";
        }
    }
}

$usernameApiPromise = $usernameClient->postAsync('generate-username');
$usernamePromise = new Promise(function () use ($usernameApiPromise, &$usernamePromise) {
    $data = json_decode($usernameApiPromise->wait()->getBody());
    $usernamePromise->resolve($data->username);
});
$username = new StringWaiter($usernamePromise);

echo "Username: {$username}\n";


// there is a bit of ugliness in making each call, but you shoudl be able to hide it behind some
// helper functions.  There is lots of power in the guzzle promises implmentation, but I find it
// to be a little clunky in use.  You will want to develop wrappers that give you the patterns
// you want.

// To finish up lets visit 5-async-timing.php and see how much time we are really saving
