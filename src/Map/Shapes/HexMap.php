<?php

namespace Hexopia\Map\Shapes;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;

class HexMap extends Map
{
    protected $radius;

    function __construct($radius = null)
    {
        $this->radius = $radius;
    }

    public static function hex($radius)
    {
        $map = new HexMap($radius);

        for ($q = -$radius; $q <= $radius; $q++) {

            $r1 = max(-$radius, -$q - $radius);
            $r2 = min($radius, -$q + $radius);

            for ($r = $r1; $r <= $r2; $r++) {
                $map->insert(
                    new Hex($q, $r)
                );
            }
        }

        return $map;
    }
}