<?php

namespace Dataswitcher\Client\Logistics\Request\Administration;

use Dataswitcher\Client\Logistics\Repository\AdministrationRepository;
use Dataswitcher\Client\Logistics\Request\AbstractRequest;
use Dataswitcher\Client\Logistics\Request\Request;
use Dataswitcher\Client\Logistics\Resource\Administration;
use Swis\JsonApi\Client\DocumentFactory;
use Swis\JsonApi\Client\Interfaces\ItemDocumentInterface;
use Swis\JsonApi\Client\ItemHydrator;

/**
 * Get's only one administration by ID.
 *
 * Class FindOne
 * @package Dataswitcher\Client\Logistics\Request\Administration
 */
class FindOne extends AbstractRequest implements Request
{
    protected string $resourceType = 'administrations';

    protected string $resourceClassName = Administration::class;

    protected string $id;

    /**
     * All constructor.
     * @param  array  $parameters
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param  array  $options
     * @return mixed
     */
    public function do($options = [])
    {
        // init
        $itemHydrator = new ItemHydrator($this->typeMapper);

        // get all the data using the repository
        $administrationRepository = new AdministrationRepository(
            $this->documentClient,
            new DocumentFactory()
        );

        // request
        /* @var $item ItemDocumentInterface */
        $item = $administrationRepository->find($this->id);

        // hydrate
        $administration = $itemHydrator->hydrate($item->getData(), $item->toArray());

        return $administration;
    }
}
