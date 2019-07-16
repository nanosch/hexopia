<?php

namespace Hexopia\Map\Traits;

use Ds\PriorityQueue;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;
use Hexopia\Objects\Marker;

trait Pathing
{
    public function drawLine(Hex $start, Hex $target, Marker $marker = null)
    {
        if ( ! $marker) {
            $marker = new Marker();
        }

        $line = $start->linedraw(
            $target
        );

        $lineFields = array_map(function(Hex $hex) use ($marker) {
            return MapField::makeForHex($hex, $marker);
        }, $line);

        $this->putAll($lineFields);
    }

    public function reachable($movement, MapField $start = null)
    {
        if (!$start) {
            $start = $this->getField(Hex::make(0, 0));
        }

        $visited[] = $start;

        $fringes = [
            [$start]
        ];

        for ($i = 1; $i <= $movement; $i++) {
            $fringes[] = [];

            foreach ($fringes[$i - 1] as $mapField) {
                foreach ($this->neighbors($mapField->hex) as $candidate) {
                    if (
                        array_search($candidate, $visited) === false &&
                        $this->isMapFieldApproachable($candidate)
                    ) {
                        $visited[] = $candidate;
                        $fringes[$i][] = $candidate;
                    }
                }
            }
        }

        return $visited;
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

            $hexApprochableNeighbors = array_map(function (MapField $mapField){
                return $mapField->hex;
            }, $this->approachableNeighbors($current));

            foreach ($hexApprochableNeighbors as $next) {
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

            $path[] = $this->getField($previous);

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
}