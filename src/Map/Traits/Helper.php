<?php

namespace Hexopia\Map\Traits;

use Hexopia\Contracts\MapObject;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;
use Hexopia\Objects\Obstacle;

trait Helper
{
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
     * @param MapObject $object
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

    public function isMapFieldApproachable(MapField $mapField)
    {
        return ! ($mapField->object instanceof Obstacle);
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

    public function getIterator()
    {
        foreach ($this->mapFields as $mapField) {
            yield $mapField->hex => $mapField->object;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->hasHex($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->put(MapField::make($offset, $value));
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
