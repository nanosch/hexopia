<?php

namespace Hexopia\Map\Shapes;

use Hexopia\Contracts\MapGuard;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;

class HexMap extends Map
{
    protected $radius;

    function __construct($radius = null, MapGuard $guard = null)
    {
        parent::__construct(null, $guard);

        $this->radius = $radius;
    }

    public function radius()
    {
        return $this->radius;
    }

    public static function hex($radius, MapGuard $guard = null)
    {
        $map = new static($radius, $guard);

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