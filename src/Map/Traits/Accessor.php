<?php

namespace Hexopia\Map\Traits;

use Hexopia\Contracts\MapObject;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;

trait Accessor
{
    /**
     * Check for Empty Map
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Number of MapFields
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->mapFields);
    }

    /**
     * Returns all MapFields as array.
     *
     * @return array
     */
    public function fields()
    {
        return $this->mapFields;
    }

    /**
     * Returns a array of all the hexagons in the map.
     *
     * @return array
     */
    public function hexagons()
    {
        $hex = function($mapField) {
            return $mapField->hex;
        };

        return array_map($hex, $this->mapFields);
    }

    /**
     * Returns a array of all the associated objects in the Map.
     *
     * @return array
     */
    public function objects(): array
    {
        $objects = function($mapField) {
            return $mapField->object;
        };

        $objects = array_map($objects, $this->mapFields);

        return array_filter($objects, function ($obj) {
            return $obj !== null;
        });
    }

    /**
     * Check if Hex exists in Map.
     *
     * @param Hex $hex
     * @return bool
     */
    public function hasHex(Hex $hex)
    {
        if ($this->lookupHex($hex) !== null) {
            return true;
        }

        return false;
    }

    /**
     * Returns whether an association for a given value exists.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function hasObject(MapObject $value): bool
    {
        return $this->lookupObject($value) !== null;
    }

    /**
     * Returns the associated object to an hex.
     *
     * @param Hex $hex
     * @return Object
     */
    public function get(Hex $hex)
    {
        return $this->lookupHex($hex) ? $this->lookupHex($hex)->object : null;
    }

    /**
     * Returns the MapField for an hex.
     *
     * @param Hex $hex
     * @return MapField
     */
    public function getField(Hex $hex)
    {
        return $this->lookupHex($hex);
    }

    public function hasNeighbor(Hex $hex, $i)
    {
        $candidate = $hex->neighbor($i);

        return $this->lookupHex($candidate) !== null;
    }

    /**
     * Returns a single hex neighbor at position $i
     *
     * @param Hex $hex - lookup
     * @param $i - position of neighbor
     * @return MapField|null neighbor as MapField
     */
    public function neighbor(Hex $hex, $i)
    {
        if (! $this->hasNeighbor($hex, $i)) {
            return null;
        }

        $neighbor = $hex->neighbor($i);

        return new MapField($neighbor, $this->get($neighbor));
    }

    /**
     * Returns all neighbors of a hex
     *
     * @param Hex $hex - lookup
     *
     * @return array hex neighbors
     */
    public function neighbors(Hex $hex)
    {
        $neighbors = [];

        for ($i = 0; $i < 6; $i++) {
            if (($candidate = $this->neighbor($hex, $i)) !== null) {
                $neighbors[] = $candidate;
            }
        }

        return $neighbors;
    }

    public function approachableNeighbors(Hex $hex)
    {
        $candidates = $this->neighbors($hex);

        return array_filter($candidates, function($candidate){
            return $this->isMapFieldApproachable($candidate);
        });
    }
}
