<?php

use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Client;

beforeEach(function () {
    $apiCaller = new ApiCaller(
        getenv('LOGISTICS_API_BASE_URI'),
        [
            'domain' => getenv('AUTH0_DOMAIN'),
            'client_id' => getenv('AUTH0_CLIENT_ID'),
            'client_secret' => getenv('AUTH0_CLIENT_SECRET'),
            'audience' => getenv('AUTH0_AUDIENCE'),
        ]
    );

    $this->client = Client::make($apiCaller);
});

it('has administrations', function () {
    $response = $this->client->administrationsAll([]);
    expect(count($response))->toBeGreaterThan(0);
});
