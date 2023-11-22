<?php

namespace Dataswitcher\Client\Logistics\Helper\Client;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP Client class that extends Guzzle client and implements the ClientInterface.
 *
 * This is needed because here we use Guzzle version 6. The project dataswitcher_aws is coupled to Guzzle 6 because
 * of fixer.io that only works with Guzzle 6.

 * The Guzzle version 6 is not compatible with our JSON API client package (swisnl/json-api-client), and
 * this class circumvents that.
 *
 * Class HttpClient
 * @package Dataswitcher\Client\Logistics\Helper\Client
 */
class HttpClient extends Client implements ClientInterface
{

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->send($request);
    }
}
