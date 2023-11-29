<?php

use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GuzzleHttp\Exception\RequestException;


beforeEach(function () {
    $this->LOGISTICS_ACCESS_TOKEN = 'dw_log_cl_access_token';   // bearer token cache key

    $cache = new FilesystemAdapter();
    $cache->deleteItem($this->LOGISTICS_ACCESS_TOKEN);

    $apiCallerInvalidCredentials = new ApiCaller(
        getenv('LOGISTICS_API_BASE_URI'),
        [
            'domain' => getenv('AUTH0_DOMAIN'),
            'client_id' => '#dirty_' . getenv('AUTH0_CLIENT_ID'),
            'client_secret' => '123!_' . getenv('AUTH0_CLIENT_SECRET'),
            'audience' => getenv('AUTH0_AUDIENCE'),
        ]
    );

    $this->auth0ErrorClient = Client::make($apiCallerInvalidCredentials);
});

it('Auth0 invalid credentials', function () {

    try {
        $response = $this->auth0ErrorClient->administrationsAll([]);
    } catch (RequestException $e) {

        expect($e->getResponse()->getStatusCode())->toBe(401);
        expect($e->getResponse()->getBody()->getContents())->toContain('Unauthorized');

    }

});
