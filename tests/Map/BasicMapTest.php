<?php

namespace Test;

use Hexopia\Hex\Helpers\HexArr;
use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexObstacle;
use Hexopia\Hex\Types\HexTypes;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Objects\Obstacle;
use Hexopia\Objects\Unit;
use Tests\Mocks\SampleHeroObject;
use Tests\Mocks\SampleMonsterObject;
use Tests\Mocks\SampleUnitGuard;

class BasicMapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function get_a_mapfield_for_associated_hex()
    {
        $map = new Map();

        $mapField = MapField::makeEmpty(0, 0);

        $map->put($mapField);

        $this->assertEquals($mapField->object, $map->get($mapField->hex));
        $this->assertEquals($mapField, $map->getField($mapField->hex));
    }

    /**
     * @test
     */
    public function check_empty_map()
    {
        $map = new Map();
        $mapField = MapField::makeEmpty(0, 0);

        $this->assertTrue($map->isEmpty());

        $map->put($mapField);

        $this->assertFalse($map->isEmpty());
    }

    /**
     * @test
     */
    public function check_map_count()
    {
        $map = new Map();
        $mapField[] = MapField::makeEmpty(0, 0);
        $mapField[] = MapField::makeEmpty(0, -1);

        $map->putAll($mapField);

        $this->assertEquals(2, $map->count());
    }

    /**
     * @test
     */
    public function create_new_map_put_mapFields()
    {
        $map = new Map();

        $mapField = MapField::makeEmpty(0, 0);

        $map->put($mapField);

        $this->assertCount(1, $map->fields());

        $this->assertEquals($mapField->hex, $map->fields()[$mapField->hex->hash()]->hex);
    }

    /**
     * @test
     */
    public function create_new_map_put_many_mapFields()
    {
        $map = new Map();

        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::makeEmpty(-1, 0);
        $mapFields[] = MapField::makeEmpty(0, 1);

        $map->putAll($mapFields);

        $this->assertCount(3, $map->fields());

        foreach ($mapFields as $mapField) {
            $this->assertEquals($mapField->hex, $map->fields()[$mapField->hex->hash()]->hex);
        }
    }


    /**
     * @test
     */
    public function create_new_map_with_many_mapFields()
    {

        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::makeEmpty(-1, 0);
        $mapFields[] = MapField::makeEmpty(0, 1);

        $map = new Map($mapFields);

        $this->assertCount(3, $map->fields());

        foreach ($mapFields as $mapField) {
            $this->assertEquals($mapField->hex, $map->fields()[$mapField->hex->hash()]->hex);
        }
    }

    /**
     * @test
     */
    public function guard_overwriting_of_map_fields()
    {

        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::makeEmpty(-1, 0);
        $mapFields[] = MapField::make(
            0, 1,
            new SampleHeroObject()
        );

        $monster = MapField::make(
            0, 1,
            new SampleMonsterObject()
        );

        $map = new Map($mapFields, new SampleUnitGuard());

        $couldOverwrite = $map->put($monster);

        $this->assertFalse($couldOverwrite);

        $this->assertCount(3, $map->fields());
        $this->assertEquals($mapFields[2]->object, $map->get($mapFields[2]->hex));
    }


}