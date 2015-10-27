<?php

// https://en.wikipedia.org/wiki/Microservices

// We build APIs were our own sites are the main consumers
// We keep each API small and make lots of them
// We aggregate them all together in our our apps

// The example-microservice directory contains 3 small apis
// written using Lumen [http://lumen.laravel.com/]

// Api generates a random username
// Api that generates a random password
// Api that creates a random email (slowly)

// To run an api, cd to its dir and run
// php artisan serve

// The app expects each app to run on a different port
// cd example-microservice/username-api
// php artisan serve --port=8000
// cd example-microservice/password-api
// php artisan serve --port=8001
// cd example-microservice/email-api
// php artisan serve --port=8002

// Our example app will create fake users

// Check out the example-microservice dir if you are interested in the backend
// Or move on to 4-async-guzzle.php
