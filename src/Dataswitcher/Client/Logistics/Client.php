<?php

namespace Dataswitcher\Client\Logistics;

use Dataswitcher\Client\Logistics\Request\Request as LogisticsRequest;
use Exception;
use RuntimeException;

/**
 * The Client, allows to interact with the logistics API server.
 *
 * Class Client
 * @package Dataswitcher\Client\Logistics
 *
 * @method administrationsAll($pageLimit = 15, $pageOffset = 0)
 * @method administrationFindOne($id)
 */
class Client
{
    private ApiCaller $apiCaller;
    private ?LogisticsRequest $request = null;

    private function __construct(
        ApiCaller $apiCaller
    ) {
        $this->apiCaller = $apiCaller;
    }

    public static function make(
        ApiCaller $apiCaller
    ): Client {
        return new self($apiCaller);
    }

    /**
     * @inheritDoc
     */
    public function __call($name, $arguments)
    {
        $requestClass = $this->resolveClass($name, 'Request');

        if (!class_exists($requestClass)) {
            throw new RuntimeException('Client failed: request ' . $name . ' is not defined.');
        }

        // request
        $request = $this->buildRequestInstance($requestClass, $arguments);
        $request->init(
            $this->apiCaller->getBaseUri(),
            $this->apiCaller->getAuth0Options()
        );

        // response
        return $this->apiCaller->call($request);
    }

    public function setRequestInstance(LogisticsRequest $request)
    {
        $this->request = $request;
    }

    private function buildRequestInstance($requestClass, $arguments)
    {
        if (!$this->request) {
            $this->request = new $requestClass(...$arguments);
        }

        return $this->request;
    }

    /**
     * Resolves to a fully qualified class name.
     *
     * @param  string  $name
     * @return string
     */
    private function resolveClass($name, $type)
    {
        // find where is the first upper case letter
        // is a namespace
        $firstUpperPos = (int)strcspn($name, 'ABCDEFGHJIJKLMNOPQRSTUVWXYZ') . PHP_EOL;

        // place the \ before the first upper case letter
        $name = substr_replace($name, '\\', $firstUpperPos, 0);

        // get full qualified class name
        return 'Dataswitcher\\Client\\Logistics\\' . $type . '\\' . ucfirst($name);
    }
}
