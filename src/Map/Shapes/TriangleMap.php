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
        parent::__construct();

        $this->size = $size;
    }
    
    public function size()
    {
        return $this->size;
    }

    public static function triangle($size)
    {
        $map = new self($size);

        for ($q = 0; $q <= $size; $q++) {
            for ($r = 0; $r <= $size - $q; $r++) {
                $map->put(
                    MapField::makeEmpty($q, $r)
                );
            }
        }

        return $map;
    }
}