<?php

namespace Tests\Map;

use Hexopia\Hex\Helpers\HexArr;
use Hexopia\Hex\Hex;
use Hexopia\Map\MapField;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Objects\Object;
use Hexopia\Objects\Obstacle;
use Hexopia\Objects\Unit;
use Tests\Mocks\SampleHeroObject;
use Tests\Mocks\SampleMonsterObject;

class CoreMapTest extends \PHPUnit\Framework\TestCase
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

        $this->assertCount($anzHex, $map->fields());
    }

    /**
     * @test
     */
    public function place_custom_hex()
    {
        $map = HexMap::hex(1);

        $unit = new Unit();

        $map->put(
            MapField::make(0, 0, $unit)
        );

        $heroInMap = false;

        foreach ($map->fields() as $mapField) {
            if ($mapField->object && $mapField->object->getType() == Object::UNIT) {
                $heroInMap = true;
            }
        }

        $this->assertCount(7, $map->fields());
        $this->assertTrue($heroInMap);
    }

    /**
     * @test
     */
    public function search_map_for_hex()
    {
        $map = HexMap::hex(2);

        $this->assertNotNull($map->getField(
            new Hex(0, 0)
        ));

        $this->assertNotNull($map->getField(
            new Hex(-2, 0)
        ));

        $this->assertNotNull($map->getField(
            new Hex(-1, 1)
        ));

        $this->assertNotNull($map->getField(
            new Hex(2, -1)
        ));

        $this->assertNull($map->getField(
            new Hex(-2, -1)
        ));

        $this->assertNull($map->getField(
            new Hex(3, -3)
        ));
    }

    /**
     * @test
     */
    public function get_map_neighbors()
    {
        $map = HexMap::hex(1);

        foreach ($map->fields() as $mapField) {
            $neighbors = array_map(function (MapField $neighbor){
                return $neighbor->hex;
            }, $map->neighbors($mapField->hex));

            if ($mapField->hex->equals(new Hex(0,0))) {
                $this->assertCount(6, $neighbors);
            } else {
                $this->assertCount(3, $neighbors);
            }

            for ($i = 0; $i < 6; $i++) {
                if ($map->hasNeighbor($mapField->hex, $i)) {
                    $this->assertNotFalse(
                        HexArr::search($map->neighbor($mapField->hex, $i)->hex, $neighbors)
                    );
                } else {
                    $this->assertFalse(
                        HexArr::search($map->neighbor($mapField->hex, $i)->hex, $neighbors)
                    );
                }
            }
        }
    }
    
    /**
     * @test
     */
    public function get_map_neighbor_with_object()
    {
        $map = HexMap::hex(1);

        $mapField = MapField::make(0,1, new SampleHeroObject());

        $map->put(
            $mapField
        );

        $this->assertEquals($mapField, $map->neighbor(Hex::make(0, 1), 6));
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
    public function map_get_approachable_neighbors()
    {
        $map = HexMap::hex(1);

        $map->put(
            MapField::make(1, -1, new Obstacle())
        );

        $map->put(
            MapField::make(-1, 1, new Obstacle())
        );

        $approachable = $map->approachableNeighbors(
            new Hex(0, 0)
        );

        $this->assertCount(4, $approachable);

        $approachableHexagons = array_map(function (MapField $field){
            return $field->hex;
        }, $approachable);

        $this->assertNotFalse(HexArr::search(new Hex(-1, 0), $approachableHexagons));

        $this->assertNotFalse(HexArr::search(new Hex(0, -1), $approachableHexagons));

        $this->assertNotFalse(HexArr::search(new Hex(0, 1), $approachableHexagons));

        $this->assertNotFalse(HexArr::search(new Hex(1, 0), $approachableHexagons));
    }

    /**
     * @test
     */
    public function place_object_ob_map_field()
    {
        $map = HexMap::hex(1);

        $unit = new Unit();

        $map->place(Hex::make(0, 0), $unit);

        $this->assertEquals($unit, $map->get(Hex::make(0, 0)));
    }
}