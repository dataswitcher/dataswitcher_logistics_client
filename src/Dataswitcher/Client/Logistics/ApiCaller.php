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

        $requiredKeys = ['domain', 'client_id', 'client_secret', 'audience'];
        $missingKeys = [];

        foreach ($requiredKeys as $key) {
            if (!isset($auth0Options[$key]) || empty($auth0Options[$key])) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            $errorMessage = 'Client failed: Missing or empty keys: ' . implode(', ', $missingKeys) . '.';
            throw new RuntimeException($errorMessage);
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
