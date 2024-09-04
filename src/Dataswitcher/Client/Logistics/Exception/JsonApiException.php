<?php

namespace Dataswitcher\Client\Logistics\Exception;

use Exception;

class JsonApiException extends Exception
{
    protected array $errors;

    public function __construct(string $message, array $errors = [], int $code = 0, Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}