<?php

namespace Test;

use Hexopia\Map\Map;
use Hexopia\Map\MapField;
use Tests\Mocks\SampleMonsterObject;

class MapAccessorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function get_a_map_field_for_associated_hex()
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
    public function check_map_has_hex()
    {
        $map = new Map();
        $mapField[] = MapField::makeEmpty(0, 0);
        $mapField[] = MapField::makeEmpty(0, -1);

        $map->putAll($mapField);

        $this->assertTrue($map->hasHex( $mapField[0]->hex ));
    }

    /**
     * @test
     */
    public function check_map_has_object()
    {
        $map = new Map();
        $mapField[] = MapField::makeEmpty(0, 0);
        $mapField[] = MapField::make(0, -1, new SampleMonsterObject());

        $map->putAll($mapField);

        $this->assertTrue($map->hasObject( $mapField[1]->object ));
    }

    /**
     * @test
     */
    public function get_all_map_hexagons()
    {
        $map = new Map();
        $mapField[] = MapField::makeEmpty(0, 0);
        $mapField[] = MapField::make(0, -1, new SampleMonsterObject());

        $map->putAll($mapField);

        $this->assertEquals([
            $mapField[0]->hex->hash() => $mapField[0]->hex,
            $mapField[1]->hex->hash() => $mapField[1]->hex,
        ], $map->hexagons());
    }

    /**
     * @test
     */
    public function get_all_map_objects()
    {
        $map = new Map();
        $mapField[] = MapField::makeEmpty(0, 0);
        $mapField[] = MapField::make(0, -1, new SampleMonsterObject());

        $map->putAll($mapField);

        $this->assertEquals([
            $mapField[1]->hex->hash() => $mapField[1]->object,
        ], $map->objects());
    }
}