<?php

namespace Hexopia\Map;

use Hexopia\Hex\Helpers\HexArr;
use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Hex\Types\HexObstacle;
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
        return HexArr::search($hex, $this->hexagons);
    }

    public function hasNeighbor(Hex $hex, $i)
    {
        $candidate = $hex->neighbor($i);

        return $this->search($candidate) !== false;
    }
    
    public function neighbor(Hex $hex, $i)
    {
        $key = $this->search($hex->neighbor($i));

        return $key !== false ? $this->hexagons[$key] : null;
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

    public function reachable($movement, Hex $start = null)
    {
        if (!$start) {
            $start = new Hex(0, 0);
        }

        $visited = [$start];

        $fringes = [
            [$start]
        ];

        for ($i = 1; $i <= $movement; $i++) {
            $fringes[] = [];

            foreach ($fringes[$i - 1] as $hex) {
                for ($direction = 0; $direction < 6; $direction++) {
                    $candidate = $this->neighbor($hex, $direction);

                    if (
                        $candidate &&
                        HexArr::search($candidate, $visited) === false &&
                        ! ($candidate->type instanceof HexObstacle)
                    ) {
                        $visited[] = $candidate;
                        $fringes[$i][] = $candidate;
                    }
                }
            }
        }

        return $visited;
    }

    public function movementRange(Hex $target)
    {
        /*
        function hex_reachable(start, movement):
            var visited = set() # set of hexes
            add start to visited
            var fringes = [] # array of arrays of hexes
            fringes.append([start])

            for each 1 < k ≤ movement:
                fringes.append([])
                for each hex in fringes[k-1]:
                    for each 0 ≤ dir < 6:
                        var neighbor = hex_neighbor(hex, dir)
                        if neighbor not in visited and not blocked:
                            add neighbor to visited
                            fringes[k].append(neighbor)

            return visited
         */
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