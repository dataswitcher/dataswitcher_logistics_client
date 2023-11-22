<?php

require __DIR__ . '/../vendor/autoload.php';

use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Client;

function generateClient(): Client
{
    $apiCaller = new ApiCaller(
        '<api url>',
        [
            'domain'        => '<domain>',
            'client_id'     => '<client id>',
            'client_secret' => '<client secret>',
            'audience'      => '<audience>',
        ]
    );

    return Client::make($apiCaller);
}

$client = generateClient();

// Uncomment what you need.

// Get all administrations.
// var_dump($client->administrationsAll());

// Get one administration.
// $administrationId= 'aaaa-1111-aaaa-1111';
// var_dump($client->administrationFindOne($administrationId));

// Change administration state.
// $administrationId = 'aaaa-1111-aaaa-1111';
// $state = 'data_requested';
// var_dump($client->workflowChangeState($administrationId, $state));
