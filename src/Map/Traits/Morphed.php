<?php

namespace Hexopia\Map\Traits;

use Ds\Collection;
use Hexopia\Map\Map;

trait Morphed
{
    /**
     * Returns a shallow copy of the collection.
     *
     * @return Collection a copy of the collection.
     */
    function copy(): Collection
    {
        return new static($this->mapFields);
    }


    /**
     * Returns a new map containing only the objects for which a predicate
     * returns true. A boolean test will be used if a predicate is not provided.
     *
     * @param callable|null $callback Accepts a hex and a object, and returns:
     *                                true : include the object,
     *                                false: skip the object.
     *
     * @return Map
     */
    public function filter(callable $callback = null): Map
    {
        $filtered = new static();

        foreach ($this->mapFields as $mapField) {
            if ($callback ? $callback($mapField) : $mapField) {
                $filtered->put($mapField);
            }
        }

        return $filtered;
    }

    /**
     * return map as array
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->mapFields as $mapField) {
            $array[$mapField->hex->hash()] = $mapField->object;
        }

        return $array;
    }
}