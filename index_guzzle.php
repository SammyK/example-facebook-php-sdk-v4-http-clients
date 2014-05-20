<?php

require_once __DIR__ . '/vendor/autoload.php';

use SammyK\FacebookGuzzleHttpClient;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;


// Inject the implementation
FacebookRequest::setHttpClientHandler(new FacebookGuzzleHttpClient());

// Add credentials
FacebookSession::setDefaultApplication('YOUR_APP_ID', 'YOUR_APP_SECRET');
$session = new FacebookSession('access-token-here');

// Get the logged in user's profile
$response = (new FacebookRequest($session, 'GET', '/me'))
    ->execute()->getGraphObject();

var_dump($response);
