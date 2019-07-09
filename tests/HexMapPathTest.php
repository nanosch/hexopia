<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Hex\Types\HexObstacle;
use Hexopia\Map\ConsolePlotter\MapPlotter;
use Hexopia\Map\Shapes\HexMap;

class HexMapPathTest extends \PHPUnit\Framework\TestCase
{
    public function reachableHexProvider()
    {
        return[
            [0, -1, 1], [1, 0, 1], [1, 1, 2],
            [2, -1, 3], [0, -3, 5], [0, -4, 6],
            [0, 3, 10], [0, 4, 10], [-3, 3, 14],
        ];
    }

    /**
     * test
     *
     * @dataProvider reachableHexProvider
     */
    public function reachable_in_map($q, $r, $distance)
    {
        $map = HexMap::hex(4);

        $obstacles = $this->createObstacles();

        $map->placeMany($obstacles);

        $range = $map->movementRange(new Hex($q, $r));

        $this->assertEquals($distance, $range);
    }

    public function InMovementRange()
    {
        // range, count, example, falseExample
        return [
            [1, 5, new Hex(0, -1), new Hex(1, -1)],
            [2, 8, new Hex(-2, 0), new Hex(-2, 2)],
            [4, 23, new Hex(3, -2), new Hex(4, 0)],
        ];
    }

    /**
     * @test
     *
     * @dataProvider InMovementRange
     */
    public function in_movement_range($movement, $hexCount, $inRange, $outofRange)
    {
        $map = HexMap::hex(5);

        $obstacles = $this->createObstacles();

        $map->placeMany($obstacles);

        $reachable = $map->reachable($movement);

        foreach ($reachable as $highlight) {
            $highlight->type = new HexHighlighted();
        }

        $map->placeMany($reachable);

        $map->place(
            new Hex(0,0, new HexHero())
        );

        $plotter = MapPlotter::draw($map);

        echo PHP_EOL;

        $plotter->plot();
        // die();

        $this->assertCount($hexCount, $reachable);

        $isInRange = false;
        $isOutOfRange = true;

        foreach ($reachable as $item) {
            if ($item->equals($inRange)) {
                $isInRange = true;
            }

            if ($item->equals($outofRange)) {
                $isOutOfRange = false;
            }
        }

        $this->assertTrue($isInRange);
        $this->assertTrue($isOutOfRange);
    }

    function createObstacles() {
        $obstacles[] = new Hex(
            1, -1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            2, -1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            2, 0, new HexObstacle()
        );

        $obstacles[] = new Hex(
            2, 1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            1, 2, new HexObstacle()
        );

        $obstacles[] = new Hex(
            0, 2, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -1, 2, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -1, 1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -2, 1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -3, 2, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -4, 3, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -5, 4, new HexObstacle()
        );

        $obstacles[] = new Hex(
            -1, -1, new HexObstacle()
        );

        $obstacles[] = new Hex(
            0, -2, new HexObstacle()
        );

        $obstacles[] = new Hex(
            1, -3, new HexObstacle()
        );

        return $obstacles;
    }
}