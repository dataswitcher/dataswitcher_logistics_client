<?php

namespace Dataswitcher\Client\Logistics\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Get's the authentication token from Auth0, and caches it locally in the file system.
 *
 * Class JWTFetcher
 * @package Dataswitcher\Client\Logistics\Helper
 */
class JWTFetcher
{
    private const LOGISTICS_ACCESS_TOKEN = 'dw_log_cl_access_token';

    private CacheInterface $cache;

    private ClientInterface $httpClient;

    private string $auth0Domain;

    /**
     * JWTFetcher constructor.
     * @param $auth0Options
     */
    public function __construct($auth0Options)
    {
        $this->httpClient = new Client(
            [
                'body' => json_encode(
                    [
                        'client_id' => $auth0Options['client_id'],
                        'client_secret' => $auth0Options['client_secret'],
                        'audience' => $auth0Options['audience'],
                        'grant_type' => 'client_credentials',
                    ]
                ),
                'headers' => [
                    'content-type' => 'application/json',
                ]
            ]
        );
        $this->cache = new FilesystemAdapter();
        $this->auth0Domain = $auth0Options['domain'];
    }

    /**
     * @return mixed|null
     * @throws InvalidArgumentException
     */
    public function fetchToken()
    {
        $accessToken = $this->cache->get(
            self::LOGISTICS_ACCESS_TOKEN,
            function (ItemInterface $item) {
                $response = $this->httpClient->request(
                    'POST',
                    'https://'.$this->auth0Domain.'/oauth/token'
                );

                if ($response->getStatusCode() !== 200) {
                    throw new RuntimeException('Access token from Auth0 failed to obtain.');
                }

                $responseData = json_decode($response->getBody(), true);

                $item->expiresAfter($responseData['expires_in']);

                return $responseData['access_token'];
            }
        );

        return $accessToken;
    }
}
