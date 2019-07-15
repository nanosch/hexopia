<?php

namespace Hexopia\Map;

use Ds\Collection;
use Ds\PriorityQueue;
use Hexopia\Contracts\MapGuard;
use Hexopia\Contracts\Object;
use Hexopia\Hex\Helpers\HexArr;
use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Hex\Types\HexObstacle;
use Hexopia\Hex\Types\HexTypes;
use Hexopia\Map\Helpers\LazyMapGuard;
use Hexopia\Objects\Obstacle;

class Map
{
    /**
     * @var array internal array to store MapFields
     */
    protected $mapFields = [];

    /**
     * @var MapGuard – protect overwriting of MapFields.
     */
    protected $guard;

    public function __construct($fields = null, MapGuard $guard = null)
    {
        $this->guard = $guard ? $guard : new LazyMapGuard();

        if ($fields) {
            $this->putAll($fields);
        }
    }
    
    // Helpers

    /**
     * Attempts to look up a Hex in the table.
     *
     * @param Hex $hex
     *
     * @return MapField|null
     */
    private function lookupHex(Hex $hex)
    {
        if (array_key_exists($hex->hash(), $this->mapFields)) {
            return $this->mapFields[$hex->hash()];
        }

        return null;
    }

    /**
     * Attempts to look up a object in the table.
     *
     * @param Object $object
     *
     * @return MapField|null
     */
    private function lookupObject(Object $object): MapField
    {
        foreach ($this->mapFields as $mapField) {
            if ($mapField->object === $object) {
                return $mapField;
            }
        }

        return null;
    }

    // Accessor
    /**
     * Check for Empty Map
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * Number of MapFields
     *
     * @return int
     */
    public function count()
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
    public function hasObject(Object $value): bool
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
        return $this->lookupHex($hex)->object;
    }

    /**
     * Returns the MapField for an hex.
     *
     * @param Hex $hex
     * @return Object
     */
    public function getField(Hex $hex)
    {
        return $this->lookupHex($hex);
    }

    // Manipulator

    /**
     * Put an MapField into the Map. This is overwriting an existing object if it's already placed.
     *
     * @param MapField $mapField
     * @return bool
     */
    public function put(MapField $mapField): bool
    {

        if ( $this->hasHex($mapField->hex) && ! $this->guard->guard(
                $this->mapFields[$mapField->hex->hash()],
                $mapField
            )
        ) {
            return false;
        }

        $this->mapFields[$mapField->hex->hash()] = $mapField;

        return true;
    }

    /**
     * Put an Array of MapField into the Map. This overwrites all existing object if they're already placed.
     *
     * @param MapField[] $fields
     */
    public function putAll(array $fields)
    {
        foreach ($fields as $mapField) {
            $this->mapFields[$mapField->hex->hash()] = $mapField;
        }
    }
}