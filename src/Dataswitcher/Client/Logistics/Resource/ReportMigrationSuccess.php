<?php

namespace Dataswitcher\Client\Logistics\Resource;

use Swis\JsonApi\Client\Meta;

/**
 * ReportMigrationSuccess resource represents the migration report data from the JSON API server.
 *
 * Class ReportMigrationSuccess
 * @package Dataswitcher\Client\Logistics\Resource
 */
class ReportMigrationSuccess extends BaseResource
{
    /** @var string */
    protected $type = 'migration-success';

    protected $attributes = [];

    protected $meta;

    public function getTotalAdministrations(): int
    {
        return $this->getAttribute('total_administrations');
    }

    public function getInProgressCount(): int
    {
        return $this->getAttribute('in_progress_count');
    }

    public function getSuccessfulCount(): int
    {
        return $this->getAttribute('successful_count');
    }

    public function getFailedCount(): int
    {
        return $this->getAttribute('failed_count');
    }
}