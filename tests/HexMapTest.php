<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
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
}