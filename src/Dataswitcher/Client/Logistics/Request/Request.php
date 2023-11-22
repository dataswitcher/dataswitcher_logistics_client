<?php

namespace Dataswitcher\Client\Logistics\Request;

/**
 * Holds all the methods that every request should implement.
 *
 * Interface Request
 * @package app\components\logistics\requests
 */
interface Request
{
    /**
     * @param  string  $logisticsBaseUri
     * @param  array  $auth0Options
     * @return void
     */
    public function init($logisticsBaseUri, $auth0Options);

    /**
     * @return mixed
     */
    public function do();
}
