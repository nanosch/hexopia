<?php

namespace Hexopia\Map;

use Hexopia\Hex\Hex;

class Map
{
    protected $radius;
    protected $hexagons = [];

    function __construct($radius = null)
    {
        $this->radius = $radius;
    }

    public static function hex($radius)
    {
        $map = new self($radius);

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

    public static function triangle($size)
    {
        $map = new self($size);

        for ($q = 0; $q <= $size; $q++) {
            for ($r = $size - $q; $r <= $size; $r++) {
                $map->insert(new Hex($q, $r));
            }
        }

        return $map;
    }

    protected function insert(Hex $hex)
    {
        $this->hexagons[] = $hex;
    }

    public function place(Hex $replacement)
    {
        foreach ($this->hexagons as $key => $hex) {
            if ($replacement->equals($hex)) {
                $this->hexagons[$key] = $replacement;
            }
        }
    }

    function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->$name;
        }

        if (method_exists(self::class, $name)) {
            return $this->$name();
        }
    }
}