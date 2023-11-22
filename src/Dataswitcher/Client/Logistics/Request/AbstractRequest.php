<?php

namespace Dataswitcher\Client\Logistics\Request;

use Dataswitcher\Client\Logistics\Helper\Client\HttpClient;
use Dataswitcher\Client\Logistics\Helper\JWTFetcher;
use Swis\JsonApi\Client\DocumentClient;
use Swis\JsonApi\Client\TypeMapper;

/**
 * Holds all the common code the Requests classes do.
 *
 * Class AbstractRequest
 * @package app\components\logistics\requests
 */
abstract class AbstractRequest
{
    protected string $apiVersion = 'v1';

    protected DocumentClient $documentClient;

    protected TypeMapper $typeMapper;

    protected string $resourceType = '';

    protected string $resourceClassName = '';

    /**
     * AbstractRequest constructor.
     * @param  string  $baseUri
     * @param  array  $auth0Options
     */
    public function init($baseUri, $auth0Options)
    {
        // http client
        $tokenFetcher = new JWTFetcher($auth0Options);
        $httpClient = new HttpClient(
            [
                'base_uri' => $baseUri.'/'.$this->apiVersion.'/',
                'headers' => [
                    'Authorization' => 'Bearer '.$tokenFetcher->fetchToken()
                ]
            ]
        );

        // type mapper
        $this->typeMapper = new TypeMapper();
        $this->typeMapper->setMapping(
            $this->resourceType,
            $this->resourceClassName
        );

        // document client for the JSON API
        $this->documentClient = DocumentClient::create($this->typeMapper, $httpClient);
    }
}
