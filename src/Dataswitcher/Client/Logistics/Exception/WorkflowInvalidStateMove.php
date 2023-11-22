<?php

namespace Dataswitcher\Client\Logistics\Exception;

class WorkflowInvalidStateMove extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
