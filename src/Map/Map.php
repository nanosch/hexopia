<?php

namespace Hexopia\Map;

use Ds\Collection;
use Hexopia\Contracts\MapGuard;
use Hexopia\Hex\Hex;
use Hexopia\Map\Helpers\LazyMapGuard;
use Hexopia\Contracts\MapObject;

class Map implements \IteratorAggregate, \ArrayAccess, Collection
{
    use Traits\Helper, Traits\Accessor, Traits\Manipulation,
        Traits\Morphed, Traits\Pathing;

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

    public function move(Object $object, Hex $target)
    {
        $field = $this->lookupObject($object);

        if($this->guard->allow($field, MapField::makeForHex($target, $object))) {
            $this->place($target, $object);
            $this->remove($field->hex);

            return true;
        }
    }
}
