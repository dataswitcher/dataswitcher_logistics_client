<?php

use Dataswitcher\Client\Logistics\Client;
use GuzzleHttp\Exception\RequestException;
use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Helper\JWTFetcher;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

beforeEach(function () {

    $this->LOGISTICS_ACCESS_TOKEN = 'dw_log_cl_access_token';   // bearer token cache key
    $this->baseApiUrl = getenv('LOGISTICS_API_BASE_URI');

    $cache = new FilesystemAdapter();
    $cache->deleteItem($this->LOGISTICS_ACCESS_TOKEN);

    $this->auth0Options = [
        'domain'        => getenv('AUTH0_DOMAIN'),
        'client_id'     => getenv('AUTH0_CLIENT_ID'),
        'client_secret' => getenv('AUTH0_CLIENT_SECRET'),
        'audience'      => getenv('AUTH0_AUDIENCE'),
    ];

});

it('Auth0 invalid credentials', function () {

    try {

        $this->auth0Options['client_id'] = '*dirty!';
        $this->auth0Options['client_secret'] = '123!';

        $apiCallerInvalidCredentials = new ApiCaller($this->baseApiUrl, $this->auth0Options);
        $auth0ErrorClient = Client::make($apiCallerInvalidCredentials);

        $auth0ErrorClient->administrationsAll([]);

    } catch (RequestException $e) {

        expect($e->getResponse()->getStatusCode())->toBe(401);
        expect($e->getResponse()->getBody()->getContents())->toContain('Unauthorized');

    }

});

it('Auth0 invalid domain', function () {

    try {

        $this->auth0Options['domain'] = 'invalid-dev.eu.auth0.com';

        $apiCallerInvalidDomain = new ApiCaller($this->baseApiUrl, $this->auth0Options);
        $auth0DomainErrorClient = Client::make($apiCallerInvalidDomain);

        $auth0DomainErrorClient->administrationsAll([]);

    } catch (RequestException $e) {

        expect($e->getResponse()->getStatusCode())->toBe(404);
        expect($e->getResponse()->getBody()->getContents())->toContain('Unknown host: invalid-dev.eu.auth0.com');

    }

});

it('Auth0 invalid audience', function () {

    try {

        $this->auth0Options['audience'] = 'https://logistics-error.dataswitcher.services/';

        $apiCallerInvalidAudience = new ApiCaller($this->baseApiUrl, $this->auth0Options);
        $auth0AudienceErrorClient = Client::make($apiCallerInvalidAudience);

        $auth0AudienceErrorClient->administrationsAll([]);

    } catch (RequestException $e) {

        expect($e->getResponse()->getStatusCode())->toBe(403);
        expect($e->getResponse()->getBody()->getContents())->toContain("Service not enabled within domain:");

    }

});

it('Can retreive Bearer Token after valid Auth0', function () {

    $tokenFetcher = new JWTFetcher($this->auth0Options);
    $token = 'Bearer ' . $tokenFetcher->fetchToken();
    $hasValidJWTStructure = (bool) preg_match('/^Bearer [A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+$/', $token);

    $this->assertTrue($hasValidJWTStructure);

});

it('Cannot retreive Bearer Token after invalid Auth0', function () {

    $this->auth0Options['client_id'] = '*dirty!';
    $this->auth0Options['client_secret'] = '123!';

    try {

        $tokenFetcher = new JWTFetcher($this->auth0Options);
        $response = $tokenFetcher->fetchToken();

    } catch (RequestException $e) {

        expect($e->getResponse()->getStatusCode())->toBe(401);
        expect($e->getResponse()->getBody()->getContents())->toContain("Unauthorized");

    }

});
