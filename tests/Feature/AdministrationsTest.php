<?php

use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


beforeEach(function () {
    $this->LOGISTICS_ACCESS_TOKEN = 'dw_log_cl_access_token';   // cache key
    $cache = new FilesystemAdapter();
    $cache->deleteItem($this->LOGISTICS_ACCESS_TOKEN);  // token is stored by cache

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

it('returns a response from method administrationsAll', function () {

    $response = $this->client->administrationsAll([]);

    if ($response) {
        expect(count($response))->toBeGreaterThan(0);  // if already has administrations on API
    } else {
        expect($response)->toBeEmpty()->toBeArray();    // if no administration on API
    }

});


