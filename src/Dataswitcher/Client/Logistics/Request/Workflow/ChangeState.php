<?php

namespace Dataswitcher\Client\Logistics\Request\Workflow;

use Dataswitcher\Client\Logistics\Request\AbstractRequest;
use Dataswitcher\Client\Logistics\Request\Request;
use Dataswitcher\Client\Logistics\Resource\Workflow;
use Dataswitcher\Client\Logistics\Exception\WorkflowInvalidStateMove;
use Swis\JsonApi\Client\Item;
use Swis\JsonApi\Client\ItemDocument;

class ChangeState extends AbstractRequest implements Request
{
    protected string $resourceType = 'workflows';

    protected string $resourceClassName = Workflow::class;

    protected string $id;
    protected string $newState;

    public function __construct($id, string $newState)
    {
        $this->id = $id;
        $this->newState = $newState;
    }

    public function do(): bool
    {
        $workflowItem = new Item([
            'state' => $this->newState,
        ]);
        $workflowItem->setType('workflow');
        $workflowItem->setId($this->id);

        $workflowItemDocument = new ItemDocument();
        $workflowItemDocument->setData($workflowItem);

        try {
            $result = $this->documentClient->patch(
                'workflow/' . $this->id,
                $workflowItemDocument
            );
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'BAD_STATE') !== false) {
                throw new WorkflowInvalidStateMove($e->getMessage());
            }
            throw new \Exception($e->getMessage());
        }

        if ($result instanceof ItemDocument) {
            return true;
        }

        return false;
    }
}
