<?php

namespace Dataswitcher\Client\Logistics\Repository;

use Swis\JsonApi\Client\Repository;

class MigrationSuccessRepository extends Repository
{
    /** @var string */
    protected $endpoint = 'reports/migration-success';
}
