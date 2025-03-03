<?php

use Dataswitcher\Client\Logistics\Client;
use Dataswitcher\Client\Logistics\ApiCaller;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Dataswitcher\Client\Logistics\Resource\ReportMigrationSuccess;


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

it('returns a response from method reportsMigrationSuccess', function () {

    $filters = ['date_start' => '2024-01-01', 'date_end' => '2024-03-01'];
    $response = $this->client->reportsMigrationSuccess($filters);

    $attributes = $response->attributes ?? [];
    $filtersMeta = (array) ($response->meta['filters'] ?? []);

    expect($response)->toBeInstanceOf(ReportMigrationSuccess::class);
    expect($attributes['total_administrations'] ?? null)->toBeInt();
    expect($attributes['in_progress_count'] ?? null)->toBeInt();
    expect($attributes['successful_count'] ?? null)->toBeInt();
    expect($attributes['failed_count'] ?? null)->toBeInt();
    expect($filtersMeta['date_start'] ?? null)->toEqual($filters['date_start']);
    expect($filtersMeta['date_end'] ?? null)->toEqual($filters['date_end']);
});