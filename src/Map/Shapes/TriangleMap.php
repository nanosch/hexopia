<?php

namespace Hexopia\Map\Shapes;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;

class TriangleMap extends Map
{
    protected $size;

    function __construct($size = null)
    {
        $this->size = $size;
    }

    public static function triangle($size)
    {
        $map = new self($size);

        for ($q = 0; $q <= $size; $q++) {
            for ($r = 0; $r <= $size - $q; $r++) {
                $map->insert(
                    MapField::makeEmpty($q, $r)
                );
            }
        }

        return $map;
    }
}