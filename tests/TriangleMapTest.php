<?php

namespace Test;

use Hexopia\Contracts\Object;
use Hexopia\Map\MapField;
use Hexopia\Map\Shapes\TriangleMap;
use Hexopia\Objects\Unit;

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

        $this->assertCount($anzHex, $map->fields());
    }
    
    /**
     * @test
     */
    public function triangle_place_custom_hex()
    {
        $map = TriangleMap::triangle(2);

        $unitField = MapField::make(0, 2, new Unit());

        $map->put($unitField);

        $unitInMap = false;

        foreach ($map->fields() as $mapField) {
            if ($mapField->object && $mapField->object->getType() == Object::UNIT) {
                $unitInMap = true;
            }
        }

        $this->assertCount(6, $map->fields());
        $this->assertTrue($unitInMap);
    }
}