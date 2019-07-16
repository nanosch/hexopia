<?php

namespace Hexopia\Map\Traits;

use Hexopia\Contracts\Object;
use Hexopia\Hex\Hex;
use Hexopia\Map\Exceptions\OnlyObjectsAllowedAsMapValue;
use Hexopia\Map\MapField;

trait Manipulation
{
    /**
     * Put an MapField into the Map. This is overwriting an existing object if it's already placed.
     *
     * @param MapField $mapField
     * @return bool
     */
    public function put(MapField $mapField): bool
    {

        if ( $this->hasHex($mapField->hex) && ! $this->guard->allow(
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

    /**
     * Place an Object at a certain Hex. if Guard allows it.
     *
     * @param Hex $hex
     * @param Object $object
     *
     * @return bool
     */
    public function place(Hex $hex, Object $object)
    {
        if ($this->guard->allow($this->getField($hex), MapField::makeForHex($hex, $object))) {
            $this->mapFields[$hex->hash()]->object = $object;
        }

        return false;
    }

    /**
     * Updates all objects by applying a callback function to each object.
     *
     * @param callable $callback Accepts two arguments: $hex and $object, should
     *                           return what the updated object will be.
     *
     * @throws OnlyObjectsAllowedAsMapValue
     */
    public function apply(callable $callback)
    {
        foreach ($this->mapFields as &$mapField) {
            $candidate = $callback($mapField->hex, $mapField->object);

            if ( ! ($candidate instanceof Object || $candidate === null )) {
                throw new OnlyObjectsAllowedAsMapValue();
            }

            $mapField->object = $candidate;
        }
    }

    /**
     * Remove all MapFields from the Map
     */
    public function clear()
    {
        $this->mapFields = [];
    }

    /**
     * Removes a hex's association from the map and returns the associated object
     * or a provided default if provided.
     *
     * @param Hex $hex
     * @return Object The associated object
     *
     */
    public function remove(Hex $hex)
    {
        if (array_key_exists($hex->hash(), $this->mapFields)) {
            $object = $this->get($hex);
            $this->mapFields[$hex->hash()]->object = null;

            return $object;
        }

        return null;
    }
}