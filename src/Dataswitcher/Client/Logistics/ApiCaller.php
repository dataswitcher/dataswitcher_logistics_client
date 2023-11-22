<?php

namespace Dataswitcher\Client\Logistics;

use Dataswitcher\Client\Logistics\Request\Request;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class ApiCaller
{
    private string $baseUri;
    private array $auth0Options;

    public function __construct(string $baseUri, array $auth0Options)
    {
        if (empty($baseUri)) {
            throw new RuntimeException('Client failed: you need to provide a server url.');
        }

        if (!isset($auth0Options['domain']) ||
            !isset($auth0Options['client_id']) ||
            !isset($auth0Options['client_secret']) ||
            !isset($auth0Options['audience'])) {
            throw new RuntimeException('Client failed: you need to provide all Auth0 options.');
        }

        $this->baseUri = $baseUri;
        $this->auth0Options = $auth0Options;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getAuth0Options(): array
    {
        return $this->auth0Options;
    }

    public function call(Request $request)
    {
        return $request->do();
    }
}
