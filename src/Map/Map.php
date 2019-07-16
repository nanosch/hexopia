<?php

namespace Hexopia\Map;

use Ds\Collection;
use Ds\PriorityQueue;
use Hexopia\Contracts\MapGuard;
use Hexopia\Hex\Hex;
use Hexopia\Map\Helpers\LazyMapGuard;
use Hexopia\Objects\Marker;

class Map implements \IteratorAggregate, \ArrayAccess, Collection
{
    use Traits\Helper, Traits\Accessor, Traits\Manipulation, Traits\Morphed;

    /**
     * @var array internal array to store MapFields
     */
    protected $mapFields = [];

    /**
     * @var MapGuard – protect overwriting of MapFields.
     */
    protected $guard;

    public function __construct($fields = null, MapGuard $guard = null)
    {
        $this->guard = $guard ? $guard : new LazyMapGuard();

        if ($fields) {
            $this->putAll($fields);
        }
    }

    // pathing
    public function drawLine(Hex $start, Hex $target)
    {
        $line = $start->linedraw(
            $target
        );

        $lineFields = array_map(function(Hex $hex) {
            return MapField::makeForHex($hex, new Marker());
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