<?php

namespace Dataswitcher\Client\Logistics\Resource;

use Swis\JsonApi\Client\Item;

class BaseResource extends Item
{
    protected $attributes = [];

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->$offset;
    }

    public function setAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getFilters(): array
    {
        return $this->meta?->toArray() ?? [];
    }

    public function getEndpointFilters(): array
    {
        $filters = $this->meta?->toArray()['filters'] ?? [];
        return (array) $filters;
    }

    public function getEndpointFilter(string $key)
    {
        return $this->getEndpointFilters()[$key] ?? null;
    }

    public function getComputedFilters(): array
    {
        $computedFilters = $this->meta?->computed_filters ?? [];
        return (array) $computedFilters;
    }

    public function getComputedFilter(string $key)
    {
        return $this->getComputedFilters()[$key] ?? null;
    }

}
