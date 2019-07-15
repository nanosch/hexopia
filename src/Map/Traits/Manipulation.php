<?php

namespace Hexopia\Map\Traits;

use Hexopia\Contracts\Object;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;

trait Manipulation
{
    /**
     * Updates all values by applying a callback function to each value.
     *
     * @param callable $callback Accepts two arguments: $hex and $object, should
     *                           return what the updated object will be.
     */
    public function apply(callable $callback)
    {
        foreach ($this->hexagons as &$mapFields) {
            $mapFields->object = $callback($mapFields->hex, $mapFields->object);
        }
    }

    /**
     * Remove all MapFields from the Map
     */
    public function clear()
    {
        $this->hexagons = [];
    }

    /**
     * Associates a hex with a object, replacing a previous association if there
     * was one.
     *
     * @param MapField $mapField
     */
    public function put(MapField $mapField)
    {
        $field = $this->lookupHex($mapField->hex);

        if ($field) {
            $field->value = $mapField->object;
        } else {
            $this->hexagons[] = $mapField;
        }
    }

    /**
     * Creates associations for all hexagons and corresponding objects of either an
     * array or iterable object.
     *
     * @param MapField ...$fields
     */
    public function putAll(MapField ...$fields)
    {
        foreach ($fields as $mapField) {
            $this->put($mapField->hex, $mapField->object);
        }
    }

    /**
     * Completely removes a hexagon from the internal array by position. It is
     * important to remove it from the array and not just use 'unset'.
     *
     * @param int $position
     * @return Object
     */
    private function delete(int $position)
    {
        $mapField  = $this->hexagons[$position];
        $object = $mapField->object;

        array_splice($this->hexagons, $position, 1, null);

        return $object;
    }

    /**
     * Removes a hex's association from the map and returns the associated object
     * or a provided default if provided.
     *
     * @param Hex $hex*
     * @return Object The associated object
     *
     */
    public function remove(Hex $hex)
    {
        foreach ($this->hexagons as $position => $mapField) {
            if ($hex->equals($mapField->hex)) {
                return $mapField->object = null;
            }
        }

        return null;
    }
}