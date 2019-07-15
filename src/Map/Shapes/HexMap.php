<?php

namespace Hexopia\Map\Shapes;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;

class HexMap extends Map
{
    protected $radius;

    function __construct($radius = null)
    {
        parent::__construct();

        $this->radius = $radius;
    }

    public static function hex($radius)
    {
        $map = new static($radius);

        for ($q = -$radius; $q <= $radius; $q++) {

            $r1 = max(-$radius, -$q - $radius);
            $r2 = min($radius, -$q + $radius);

            for ($r = $r1; $r <= $r2; $r++) {
                $map->put(
                    MapField::makeEmpty($q, $r)
                );
            }
        }

        return $map;
    }
}