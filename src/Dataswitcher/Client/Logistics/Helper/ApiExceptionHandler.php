<?php

namespace Dataswitcher\Client\Logistics\Helper;

use GuzzleHttp\Exception\RequestException;
use Dataswitcher\Client\Logistics\Exception\JsonApiException;

class ApiExceptionHandler
{
    /**
     * Handles a RequestException and throws a JsonApiException with detailed information.
     *
     */
    public static function handleRequestException(RequestException $e): JsonApiException
    {
        $response = $e->getResponse();

        if ($response) {
            $statusCode = $response->getStatusCode();
            $responseBody = (string) $response->getBody();
            $responseArray = json_decode($responseBody, true);

            if (isset($responseArray['errors'])) {
                // Handle JSON API errors
                $errors = $responseArray['errors'];
                throw new JsonApiException("API error: " . $e->getMessage(), $errors, $statusCode, $e);
            } else {
                // Handle other HTTP errors
                throw new JsonApiException("HTTP request failed with status code $statusCode: " . $e->getMessage(), [], $statusCode, $e);
            }
        } else {
            // Handle the case where there's no response
            throw new JsonApiException("HTTP request failed: " . $e->getMessage());
        }
    }

    /**
     * Handles a general exception and throws a RuntimeException with detailed information.
     *
     */
    public static function handleGeneralException(\Exception $e): \RuntimeException
    {
        throw new \RuntimeException('An unexpected error occurred: ' . $e->getMessage());
    }
}