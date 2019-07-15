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
        return new static($this->hexagons);
    }

    /**
     * Returns a new map containing only the values for which a predicate
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

        foreach ($this as $hex => $object) {
            if ($callback ? $callback($hex, $object) : $object) {
                $filtered->put($hex, $object);
            }
        }

        return $filtered;
    }

    /**
     * Returns a new map using the results of applying a callback to each value.
     *
     * The hex will be equal in both maps.
     *
     * @param callable $callback Accepts two arguments: hex and object, should
     *                           return what the updated object will be.
     *
     * @return Map
     */
    public function map(callable $callback): Map
    {
        $apply = function($mapField) use ($callback) {
            return $callback($mapField->hex, $mapField->object);
        };

        return new static(array_map($apply, $this->hexagons));
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->hexagons as $mapField) {
            $array[$mapField->hex] = $mapField->object;
        }

        return $array;
    }

    /**
     * Returns a representation that can be natively converted to JSON, which is
     * called when invoking json_encode.
     *
     * @return mixed
     *
     * @see JsonSerializable
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}