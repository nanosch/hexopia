<?php

namespace Hexopia\Map;

use Ds\PriorityQueue;
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

    public function neighbors(Hex $hex)
    {
        $neighbors = [];

        for ($i = 0; $i < 6; $i++) {
            if (($candidate = $this->neighbor($hex, $i)) !== null) {
                $neighbors[] = $candidate;
            }
        }

        return $neighbors;
    }
    
    public function approachableNeighbors(Hex $hex)
    {
        $candidates = $this->neighbors($hex);

        return array_filter($candidates, function($candidate){
            return ! ($candidate->type instanceof HexObstacle);
        });
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
    
    public function pathFromTo(Hex $start, Hex $target)
    {
        $frontier = new PriorityQueue();
        $frontier->push($start, 0);
        $cameFrom = new \Ds\Map();
        $costSoFar = new \Ds\Map();
        $cameFrom->put($start, null);
        $costSoFar->put($start, 0);

        while ($frontier->count() > 0) {
            $current = $frontier->pop();

            if ($current->equals($target)) {
                break;
            }

            foreach ($this->approachableNeighbors($current) as $next) {
                $newCost = $costSoFar[$current] + $current->distance($next);

                if ( ! $costSoFar->hasKey($next) || $newCost < $costSoFar->get($next)) {
                    $costSoFar->put($next, $newCost);
                    $priority = $newCost + $next->distance($target);
                    $frontier->push($next, -$priority);
                    $cameFrom->put($next, $current);
                }
            }
        }



        if (! $cameFrom->hasKey($target)) {
            return null;
        }

        $previous = $target;

        do {

            $path[] = $previous;

        } while($previous = $cameFrom->get($previous));

        return array_reverse($path);
    }
    
    public function pathBetween(Hex $start, Hex $target)
    {
        $fullPath = $this->pathFromTo($start, $target);

        array_shift($fullPath);
        array_pop($fullPath);

        return $fullPath;
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
                foreach ($this->neighbors($hex) as $candidate) {
                    if (
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