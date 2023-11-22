<?php

namespace Dataswitcher\Client\Logistics\Request\Administrations;

use Dataswitcher\Client\Logistics\Repository\AdministrationRepository;
use Dataswitcher\Client\Logistics\Request\AbstractRequest;
use Dataswitcher\Client\Logistics\Request\Request;
use Dataswitcher\Client\Logistics\Resource\Administration;
use Swis\JsonApi\Client\DocumentFactory;
use Swis\JsonApi\Client\ItemHydrator;

/**
 * Get's all administrations.
 *
 * Class All
 * @package Dataswitcher\Client\Logistics\Request\Administrations
 */
class All extends AbstractRequest implements Request
{
    protected string $resourceType = 'administrations';

    protected string $resourceClassName = Administration::class;

    protected int $pageLimit;

    protected int $pageOffset;

    /**
     * All constructor.
     * @param  array  $parameters
     */
    public function __construct($parameters = [])
    {
        $this->pageLimit = $parameters['pageLimit'] ?? 15;
        $this->pageOffset = $parameters['pageOffset'] ?? 0;
    }

    /**
     * @param  array  $options
     * @return mixed
     */
    public function do($options = [])
    {
        // init
        $administrations = [];
        $itemHydrator = new ItemHydrator($this->typeMapper);

        // get all the data using the repository
        $administrationRepository = new AdministrationRepository(
            $this->documentClient,
            new DocumentFactory()
        );
        $parameters = ['page' => ['limit' => $this->pageLimit, 'offset' => $this->pageOffset]];
        $repositoryResult = $administrationRepository->all($parameters);

        // iterate and hydrate
        foreach ($repositoryResult->getData() as $item) {
            $administrations[] = $itemHydrator->hydrate($item, $item->getAttributes());
        }

        return $administrations;
    }
}
