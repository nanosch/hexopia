<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Types\HexTypes;
use Hexopia\Map\Shapes\TriangleMap;

class TriangleMapTest extends \PHPUnit\Framework\TestCase
{
    public function mapAssertions()
    {
        // Size, Anz-Hex
        return [
            [0, 1], [1, 3],
            [2, 6], [3, 10],
            [4, 15]
        ];
    }

    /**
     * @test
     *
     * @dataProvider mapAssertions
     */
    public function generate_triangle_map($size, $anzHex)
    {
        $map = TriangleMap::triangle($size);

        $this->assertCount($anzHex, $map->hexagons);
    }
    
    /**
     * @test
     */
    public function triangle_place_custom_hex()
    {
        $map = TriangleMap::triangle(2);

        $hero = new Hex(0, 2, new HexHero());

        $map->place($hero);

        $heroInMap = false;

        foreach ($map->hexagons as $hex) {
            if ($hex->type->value == HexTypes::HERO) {
                $heroInMap = true;
            }
        }

        $this->assertCount(6, $map->hexagons);
        $this->assertTrue($heroInMap);
    }
}