<?php

namespace Hexopia\Map;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Hex\Types\HexTypes;

class Map
{
    protected $hexagons = [];

    protected function insert(Hex $hex)
    {
        $this->hexagons[] = $hex;
    }
    
    public function search(Hex $hex)
    {
        foreach ($this->hexagons as $key => $hexagon) {
            if ($hex->equals($hexagon)) {
                return $key;
            }
        }

        return false;
    }

    public function hasNeighbor(Hex $hex, $i)
    {
        $candidate = $hex->neighbor($i);

        return $this->search($candidate) !== false;
    }

    public function place(Hex $replacement)
    {
        foreach ($this->hexagons as $key => $hex) {
            if ($replacement->equals($hex)) {
                $this->hexagons[$key] = $replacement;
            }
        }
    }

    public function placeMany(array $hexagons, HexTypes $asType = null)
    {
        foreach ($hexagons as $hexagon) {
            if ($asType) {
                $hexagon->type = $asType;
            }

            $this->place($hexagon);
        }
    }
    
    public function drawLine(Hex $start, Hex $target)
    {
        $line = $start->linedraw(
            $target
        );

        $this->placeMany($line, new HexHighlighted());
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