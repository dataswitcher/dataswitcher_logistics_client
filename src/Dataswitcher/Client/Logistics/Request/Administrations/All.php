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

    protected array $pagination;

    /**
     * All constructor.
     *
     * @param array $parameters {
     *     Filters to apply when retrieving all administrations.
     *
     *     @type int $pageNumber    Optional. Set the page Number. If omitted, 1 is applied by default.
     *     @type int $pageSize      Optional. Set the page Size, If omitted, 50 itens are applied by default.
     * }
     */
    public function __construct($parameters = [])
    {
        // transform filter into JSON:API standard filter format ['filter[<key>]' => <value>]
        $this->pagination = [
            'page[number]'  => $parameters['pageNumber'] ?? 1,
            'page[size]'    => $parameters['pageSize'] ?? 50,
        ];
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

        $repositoryResult = $administrationRepository->all($this->pagination);

        // iterate and hydrate
        foreach ($repositoryResult->getData() as $item) {
            $administrations[] = $itemHydrator->hydrate($item, $item->getAttributes());
        }

        return $administrations;
    }
}
