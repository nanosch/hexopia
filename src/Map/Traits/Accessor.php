<?php

namespace Hexopia\Map\Traits;

use Hexopia\Contracts\Object;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;
use OutOfBoundsException;
use OutOfRangeException;

trait Accessor
{
    /**
     * Returns whether the collection is empty.
     *
     * This should be equivalent to a count of zero, but is not required.
     * Implementations should define what empty means in their own context.
     *
     * @return bool
     */
    function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Returns all MapFields of the Map
     *
     * @return array
     */
    public function fields(): array
    {
        return $this->hexagons;
    }

    /**
     * Return the MapField at a specified position in the Map
     *
     * @param Hex $position
     *
     * @return MapField
     *
     * @throws OutOfRangeException
     */
    public function skip(Hex $position): MapField
    {
        if ( ! $this->lookupHex($position)) {
            throw new OutOfRangeException();
        }

        return $this->hexagons[$position]->copy();
    }

    /**
     * Returns whether an association a given key exists.
     *
     * @param bool
     *
     * @return bool
     */
    public function hasKey($hex): bool
    {
        return $this->lookupHex($hex) !== null;
    }

    /**
     * Returns whether an association for a given value exists.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function hasValue($value): bool
    {
        return $this->lookupValue($value) !== null;
    }

    /**
     * Returns the number of MapFields in the Map
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->hexagons);
    }

    /**
     * Returns the object associated with a hex, or an optional default if the
     * hex is not associated with a object.
     *
     * @param Hex $hex
     * @param Object $default
     *
     * @return Object The associated object or fallback default if provided.
     *
     */
    public function get(Hex $hex, Object $default = null)
    {
        if (($mapfield = $this->lookupHex($hex))) {
            return $mapfield->object;
        }

        // Check if a default was provided.
        if (func_num_args() === 1) {
            throw new OutOfBoundsException();
        }

        return $default;
    }

    /**
     * Returns a array of all the hexagons in the map.
     *
     * @return array
     */
    public function keys(): array
    {
        $hex = function($mapField) {
            return $mapField->hex;
        };

        return array_map($hex, $this->hexagons);
    }

    /**
     * Returns a array of all the associated objects in the Map.
     *
     * @return array
     */
    public function values(): array
    {
        $object = function($mapField) {
            return $mapField->object;
        };

        return array_map($object, $this->hexagons);
    }
}