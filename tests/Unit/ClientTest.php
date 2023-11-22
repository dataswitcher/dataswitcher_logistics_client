<?php

use Dataswitcher\Client\Logistics\Client;
use Dataswitcher\Client\Logistics\ApiCaller;
use Dataswitcher\Client\Logistics\Request\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {

    // Common configuration of mocks
    $this->logisticsBaseUri = 'http://example.com';
    $this->auth0Options = [
        'domain' => 'example.auth0.com',
        'client_id' => '1234567890',
        'client_secret' => 's3cr3t',
        'audience' => 'example.com/api',
    ];
    $this->requestMock = $this->createMock(Request::class);

    $this->apiCallerMock = Mockery::mock(ApiCaller::class);
    $this->apiCallerMock->shouldReceive('getBaseUri')->andReturn(
        'https://example.com'
    );
    $this->apiCallerMock->shouldReceive('getAuth0Options')->andReturn(['auth0_options']);
    $this->apiCallerMock->shouldReceive('call')->andReturn(new Response(200, [], json_encode(['result'])));
});

it('throws exception if logistics base uri is empty', function () {
    expect(function () {
        new ApiCaller(
            '',
            [
                'domain' => 'test-domain.auth0.com',
                'client_id' => 'test-client-id',
                'client_secret' => 'test-client-secret',
                'audience' => 'test-audience',
            ]
        );
    })->toThrow(RuntimeException::class, 'Client failed: you need to provide a server url.');
});

it('throws exception if any Auth0 option is missing', function () {
    expect(function () {
        new ApiCaller(
            'http://example.com',
            [
                'domain' => 'test-domain.auth0.com',
                'client_id' => 'test-client-id',
                'client_secret' => 'test-client-secret',
            ]
        );
    })->toThrow(RuntimeException::class, 'Client failed: you need to provide all Auth0 options.');
});

it('throws exception if request class does not exist', function () {
    $client = Client::make($this->apiCallerMock);
    $client->setRequestInstance($this->requestMock);

    expect(function () use ($client) {
        $client->nonExistingRequest();
    })->toThrow(RuntimeException::class, 'Client failed: request nonExistingRequest is not defined.');
});

it('does a request correctly', function () {
    // Arrange
    $client = Client::make($this->apiCallerMock);
    $client->setRequestInstance($this->requestMock);

    // Act
    $result = $client->administrationFindOne(20, 10);

    // Assert
    expect($result)->toBeInstanceOf(Response::class);
});
