<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Types\HexObstacle;
use Hexopia\Hex\Types\HexTypes;
use Hexopia\Map\Shapes\HexMap;

class HexMapTest extends \PHPUnit\Framework\TestCase
{
    public function mapAssertions()
    {
        // Radius, Anz-Hex
        return [
            [0, 1], [1, 7],
            [2, 19], [3, 37]
        ];
    }

    /**
     * @test
     *
     * @dataProvider mapAssertions
     */
    public function generateHexagonalMap($radius, $anzHex)
    {
        $map = HexMap::hex($radius);

        $this->assertCount($anzHex, $map->hexagons);
    }
    
    /**
     * @test
     */
    public function place_custom_hex()
    {
        $map = HexMap::hex(1);

        $hero = new Hex(1, -1, new HexHero());

        $map->place($hero);

        $heroInMap = false;

        foreach ($map->hexagons as $hex) {
            if ($hex->type->value == HexTypes::HERO) {
                $heroInMap = true;
            }
        }

        $this->assertCount(7, $map->hexagons);
        $this->assertTrue($heroInMap);
    }

    /**
     * @test
     */
    public function place_many_hex_in_map()
    {
        $map = HexMap::hex(1);

        $obstacles = [
            new Hex(1, -1, new HexObstacle()),
            new Hex(0, 0, new HexObstacle())
        ];

        $map->placeMany($obstacles);

        $obstaclesInMap = 0;

        foreach ($map->hexagons as $hex) {
            if ($hex->type->value == HexTypes::OBSTACLE) {
                $obstaclesInMap++;
            }
        }

        $this->assertCount(7, $map->hexagons);
        $this->assertEquals(2, $obstaclesInMap);
    }

    /**
     * @test
     */
    public function darw_line_in_map()
    {
        $map = HexMap::hex(3);

        $start = new Hex(-2, 0);
        $target = new Hex(3, -1);

        $map->drawLine($start, $target);

        $steps = 0;

        foreach ($map->hexagons as $hex) {
            if ($hex->type->value == HexTypes::HIGHLIGHTED) {
                $steps++;
            }
        }

        $this->assertEquals(6, $steps);
        $this->assertCount(37, $map->hexagons);
    }
    
    /**
     * @test
     */
    public function search_map_for_hex()
    {
        $map = HexMap::hex(2);

        $this->assertNotFalse($map->search(
            new Hex(0, 0)
        ));

        $this->assertNotFalse($map->search(
            new Hex(-2, 0)
        ));

        $this->assertNotFalse($map->search(
            new Hex(-1, 1)
        ));

        $this->assertNotFalse($map->search(
            new Hex(2, -1)
        ));

        $this->assertFalse($map->search(
            new Hex(-2, -1)
        ));

        $this->assertFalse($map->search(
            new Hex(3, -3)
        ));
    }

    /**
     * @test
     */
    public function has_map_neighbor()
    {
        $map = HexMap::hex(1);

        for($i = 0; $i < 6; $i++){
            $this->assertTrue(
                $map->hasNeighbor(new Hex(0,0), $i)
            );
        }

        for($i = 3; $i < 6; $i++){
            $this->assertTrue(
                $map->hasNeighbor(new Hex(1, -1), $i)
            );
        }

        $this->assertTrue(
            $map->hasNeighbor(new Hex(0, -1), 0)
        );
        $this->assertTrue(
            $map->hasNeighbor(new Hex(0, -1), 4)
        );
        $this->assertTrue(
            $map->hasNeighbor(new Hex(0, -1), 5)
        );

        $this->assertTrue(
            $map->hasNeighbor(new Hex(-1, 0), 0)
        );
        $this->assertTrue(
            $map->hasNeighbor(new Hex(-1, 0), 1)
        );
        $this->assertTrue(
            $map->hasNeighbor(new Hex(-1, 0), 5)
        );

        for($i = 0; $i < 3; $i++){
            $this->assertTrue(
                $map->hasNeighbor(new Hex(-1, 1), $i)
            );
        }

        for($i = 1; $i < 4; $i++){
            $this->assertTrue(
                $map->hasNeighbor(new Hex(0, 1), $i)
            );
        }

        for($i = 2; $i < 5; $i++){
            $this->assertTrue(
                $map->hasNeighbor(new Hex(1, 0), $i)
            );
        }
    }

    /**
     * @test
     */
    public function map_hex_neighbors()
    {
        $map = HexMap::hex(1);

        foreach ($map->hexagons as $hex) {
            $neighbors = $map->neighbors($hex);

            if ($hex->equals(new Hex(0,0))) {
                $this->assertCount(6, $neighbors);
            } else {
                $this->assertCount(3, $neighbors);
            }

            for ($i = 0; $i < 6; $i++) {
                if ($map->hasNeighbor($hex, $i)) {
                    $this->assertNotFalse(
                        array_search($map->neighbor($hex, $i), $neighbors)
                    );
                } else {
                    $this->assertFalse(
                        array_search($map->neighbor($hex, $i), $neighbors)
                    );
                }
            }
        }
    }
}