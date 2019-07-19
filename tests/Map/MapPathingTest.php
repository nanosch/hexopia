<?php

namespace Tests\Map;

use Hexopia\Contracts\MapObject;
use Hexopia\Hex\Hex;
use Hexopia\Map\ConsolePlotter\MapPlotter;
use Hexopia\Map\MapField;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Objects\Marker;
use Hexopia\Objects\Obstacle;
use Hexopia\Objects\Unit;
use Tests\Mocks\SampleHeroObject;
use Tests\Mocks\SampleMonsterObject;
use Tests\Mocks\SampleUnitGuard;

class MapPathingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function darw_line_in_a_map()
    {
        $map = HexMap::hex(3);

        $start = new Hex(-2, 0);
        $target = new Hex(3, -1);

        $map->drawLine($start, $target);

        $steps = 0;

        foreach ($map->fields() as $mapField) {
            if ($mapField->object && $mapField->object->getType() == MapObject::MARKER) {
                $steps++;
            }
        }

        $this->assertEquals(6, $steps);
        $this->assertEquals(HexMap::hex(3)->hexagons(), $map->hexagons());
        $this->assertCount(37, $map->fields());
    }

    public function InMovementRange()
    {
        // range, count, example, falseExample
        return [
            [1, 5, new MapField(new Hex(0, -1)), new MapField(new Hex(1, -1))],
            [2, 8, new MapField(new Hex(-2, 0)), new MapField(new Hex(-2, 2))],
            [4, 23, new MapField(new Hex(3, -2)), new MapField(new Hex(4, 0))],
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

        $map->putAll($obstacles);

        $reachable = $map->reachable($movement);

        foreach ($reachable as $highlight) {
            $highlight->object = new Marker();
        }

        $map->putAll($reachable);

        $map->put(
            MapField::make(0, 0, new Unit())
        );

        $plotter = MapPlotter::draw($map);

        echo PHP_EOL;

        $plotter->plot();

        $this->assertCount($hexCount, $reachable);

        $isInRange = false;
        $isOutOfRange = true;

        foreach ($reachable as $mapField) {
            if ($mapField->equalField($inRange)) {
                $isInRange = true;
            }

            if ($mapField->equalField($outofRange)) {
                $isOutOfRange = false;
            }
        }

        $this->assertTrue($isInRange);
        $this->assertTrue($isOutOfRange);
    }

    public function pathDataProvider()
    {
        // start, $target, $path
        return [
            [new Hex(0,0), new Hex(-1,0), [
                new MapField(new Hex(0,0)), new MapField(new Hex(-1, 0))
            ]],
            [new Hex(0,1), new Hex(-4,2), [
                0 => new MapField(new Hex(0,1)), 1 => new MapField(new Hex(0, 0)), 2 => new MapField(new Hex(-1, 0)),
                3 => new MapField(new Hex(-2, 0)), 4 => new MapField(new Hex(-3, 1)), 5 => new MapField(new Hex(-4, 2))
            ]],
        ];
    }

    /**
     * @test
     *
     * @dataProvider pathDataProvider
     */
    public function path_From_to($start, $target, $path)
    {
        $map = HexMap::hex(5);

        $obstacles = $this->createObstacles();

        $map->putAll($obstacles);

        $this->assertEquals($path, $map->pathFromTo($start, $target));
    }

    /**
     * @test
     *
     * @dataProvider pathDataProvider
     */
    public function path_between($start, $target, $path)
    {
        $map = HexMap::hex(5);

        $obstacles = $this->createObstacles();

        $map->putAll($obstacles);

        array_shift($path);
        array_pop($path);

        $this->assertEquals($path, $map->pathBetween($start, $target));
    }

    /**
     * @test
     */
    public function move_unit()
    {
        $map = HexMap::hex(5);

        $obstacles = $this->createObstacles();

        $map->putAll($obstacles);

        $hero = new SampleHeroObject();

        $map->place(Hex::make(0,0), $hero);

        $this->assertTrue($map->move($hero, Hex::make(-4, 2)));

        $this->assertNull($map->get(Hex::make(0, 0)));
        $this->assertEquals($hero, $map->get(Hex::make(-4, 2)));
    }

    /**
     * @test
     */
    public function hinder_unit_move()
    {
        $map = HexMap::hex(5, new SampleUnitGuard());

        $obstacles = $this->createObstacles();

        $map->putAll($obstacles);

        $hero = new SampleHeroObject();
        $monster = new SampleMonsterObject();

        $this->assertTrue($map->place(Hex::make(0,0), $hero));
        $this->assertFalse($map->place(Hex::make(0,0), $monster));
    }

    function createObstacles() {
        $obstacles[] = MapField::make(
            1, -1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            2, -1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            2, 0, new Obstacle()
        );

        $obstacles[] = MapField::make(
            2, 1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            1, 2, new Obstacle()
        );

        $obstacles[] = MapField::make(
            0, 2, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -1, 2, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -1, 1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -2, 1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -3, 2, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -4, 3, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -5, 4, new Obstacle()
        );

        $obstacles[] = MapField::make(
            -1, -1, new Obstacle()
        );

        $obstacles[] = MapField::make(
            0, -2, new Obstacle()
        );

        $obstacles[] = MapField::make(
            1, -3, new Obstacle()
        );

        return $obstacles;
    }
}
