<?php

namespace Hexopia\Map\Traits;

use Ds\Hashable;
use Hexopia\Contracts\Object;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;
use OutOfBoundsException;

trait Helper
{
    /**
     * Determines whether two hex are equal.
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return bool
     */
    private function hexAreEqual(Hex $a, Hex $b): bool
    {
        if (is_object($a) && $a instanceof Hashable) {
            return get_class($a) === get_class($b) && $a->equals($b);
        }

        return $a === $b;
    }

    /**
     * Attempts to look up a key in the table.
     *
     * @param Hex $hex
     *
     * @return MapField|null
     */
    private function lookupHex($hex)
    {
        foreach ($this->hexagons as $mapField) {
            if ($this->keysAreEqual($mapField->key, $hex)) {
                return $mapField;
            }
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
        foreach ($this->hexagons as $mapField) {
            if ($mapField->object === $object) {
                return $mapField;
            }
        }
    }


    public function getIterator()
    {
        foreach ($this->hexagons as $mapField) {
            yield $mapField->hex => $mapField->object;
        }
    }

    /**
     * Returns a representation to be used for var_dump and print_r.
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    /**
     * @inheritdoc
     *
     * @throws OutOfBoundsException
     */
    public function &offsetGet($offset)
    {
        $mapField = $this->lookupKey($offset);

        if ($mapField) {
            return $mapField->value;
        }

        throw new OutOfBoundsException();
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset, null);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->get($offset, null) !== null;
    }
}