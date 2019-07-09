<?php

namespace Hexopia\Map;

use Hexopia\Hex\Hex;

class Map
{
    protected $hexagons = [];

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
        if (property_exists(static::class, $name)) {
            return $this->$name;
        }

        if (method_exists(static::class, $name)) {
            return $this->$name();
        }
    }
}