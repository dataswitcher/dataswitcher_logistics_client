<?php

namespace Dataswitcher\Client\Logistics\Request\Reports;

use Dataswitcher\Client\Logistics\Helper\ApiExceptionHandler;
use Dataswitcher\Client\Logistics\Repository\MigrationSuccessRepository;
use Dataswitcher\Client\Logistics\Request\AbstractRequest;
use Dataswitcher\Client\Logistics\Request\Request;
use Dataswitcher\Client\Logistics\Resource\ReportMigrationSuccess;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Swis\JsonApi\Client\DocumentFactory;
use Swis\JsonApi\Client\ItemHydrator;
use Swis\JsonApi\Client\Meta;

class MigrationSuccess extends AbstractRequest implements Request
{
    protected string $resourceType = 'migration-success';

    protected string $resourceClassName = ReportMigrationSuccess::class;

    protected array $filters;

    /**
     * MigrationSuccess constructor.
     *
     * @param array $filters {
     *     Filters to apply when retrieving migration success reports.
     *
     *     @type string $date_start Required. The start date for filtering the reports. Format: 'YYYY-MM-DD'.
     *     @type string $date_end   Required. The end date for filtering the reports. Format: 'YYYY-MM-DD'.
     *     @type int $tenant_id     Optional. Filter by tenant ID. If omitted, '0' is applied (all).
     *     @type int $package_from  Optional. Filter by the starting package number. If omitted, '0' is applied (all).
     *     @type int $package_to    Optional. Filter by the ending package number. If omitted, '0' is applied (all).
     *     @type bool $with_ids     Optional. Adds Administration IDs Bag Data. If omitted, false is applied.
     * }
     */
    public function __construct(array $filters)
    {
        // transform filter into JSON:API standard filter format ['filter[<key>]' => <value>]
        $this->filters = [
            'filter[date_start]'    => (string) $filters['date_start'],
            'filter[date_end]'      => (string) $filters['date_end'],
            'filter[tenant_id]'     => (int) ($filters['tenant_id'] ?? 0),
            'filter[package_from]'  => (int) ($filters['package_from'] ?? 0),
            'filter[package_to]'    => (int) ($filters['package_to'] ?? 0),
            'filter[with_ids]'      => (bool) ($filters['with_ids'] ?? false),
        ];
    }

    public function do()
    {
        try {
            $itemHydrator = new ItemHydrator($this->typeMapper);
            $repository = new MigrationSuccessRepository(
                $this->documentClient,
                new DocumentFactory()
            );
            $repositoryResult = $repository->all($this->filters);
            $metaArray = $repositoryResult->toArray()['meta'] ?? [];

            $migrationSuccessData = $itemHydrator->hydrate($repositoryResult->getData(), []);
            $migrationSuccessData->setMeta(new Meta($metaArray));

            return $migrationSuccessData;
        } catch (RequestException $e) {
            ApiExceptionHandler::handleRequestException($e);
        } catch (Exception $e) {
            ApiExceptionHandler::handleGeneralException($e);
        }

        return null;
    }
}
