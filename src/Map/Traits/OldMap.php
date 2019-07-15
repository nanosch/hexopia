<?php

namespace Hexopia\Map;

use Ds\Collection;
use Ds\PriorityQueue;
use Hexopia\Hex\Helpers\HexArr;
use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Hex\Types\HexObstacle;
use Hexopia\Hex\Types\HexTypes;
use Hexopia\Objects\Obstacle;

class OldMap implements \IteratorAggregate, \ArrayAccess, Collection
{
    /**
     * @var array internal array to store MapFields
     */
    protected $hexagons = [];

    use Traits\Helper,
        Traits\Accessor,
        Traits\Manipulation,
        Traits\Morphed;

    public function __construct($fields = null)
    {
        if ($fields) {
            $this->putAll($fields);
        }
    }

    protected function insert(MapField $field)
    {
        $this->hexagons->put($field->hex, $field->object);
    }
    
    public function hasNeighbor(Hex $hex, $i)
    {
        $candidate = $hex->neighbor($i);

        return $this->hexagons->hasKey($candidate);
    }
    
    public function neighbor(Hex $hex, $i)
    {
        if (! $this->hasNeighbor($hex, $i)) {
            return null;
        }

        $neighbor = $hex->neighbor($i);

        return new MapField($neighbor, $this->hexagons->get($neighbor));
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
            return ! ($candidate->object instanceof Obstacle);
        });
    }

    public function place(MapField $field)
    {
//        foreach ($this->hexagons as $hex => $resident) {
//            if ($field->equals($hex)) {
//                $this->hexagons[$key] = $replacement;
//            }
//        }
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