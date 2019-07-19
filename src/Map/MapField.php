<?php

namespace Hexopia\Map;

use Hexopia\Contracts\MapObject;
use Hexopia\Hex\Hex;
use JsonSerializable;

/**
 * A pair which represents a key and an associated value.
 *
 * @package Ds
 */
final class MapField implements JsonSerializable
{
    /**
     * @param Hex $key The pair's key
     */
    public $hex;

    /**
     * @param MapObject $object The pair's value
     */
    public $object;

    /**
     * Creates a new instance.
     *
     * @param Hex $hex
     * @param MapObject $object
     */
    public function __construct(Hex $hex, MapObject $object = null)
    {
        $this->hex   = $hex;
        $this->object = $object;
    }

    public static function makeEmpty($q, $r)
    {
        return new self(
            new Hex($q, $r)
        );
    }

    public static function make($q, $r, MapObject $object)
    {
        $field = static::makeEmpty($q, $r);
        $field->object = $object;

        return $field;
    }

    public static function makeForHex(Hex $hex, MapObject $object)
    {
        return new static($hex, $object);
    }

    public function equalField(MapField $mapField)
    {
        return $this->hex->equals($mapField->hex);
    }

    /**
     * Returns a copy of the Pair
     *
     * @return MapField
     */
    public function copy(): MapField
    {
        return new self($this->hex, $this->object);
    }

    /**
     * Returns a representation to be used for var_dump and print_r.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['hex' => $this->hex, 'object' => $this->object];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Returns a string representation of the pair.
     */
    public function __toString()
    {
        return 'object(' . get_class($this) . ')';
    }
}
