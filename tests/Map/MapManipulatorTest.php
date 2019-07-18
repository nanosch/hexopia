<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;
use Tests\Mocks\SampleHeroObject;
use Tests\Mocks\SampleMonsterObject;
use Tests\Mocks\SampleUnitGuard;

class MapManipulatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function create_new_map_put_map_fields()
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
    public function create_new_map_put_many_map_fields()
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

    /**
     * @test
     */
    public function apply_callback_to_all_objects_in_map()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);

        $map->apply(function ($hex, $unit) {
            if ($unit){
                $unit->live -= 1;
            }

            return $unit;
        });

        foreach ($map->objects() as $unit) {
            $this->assertEquals(98, $unit->live);
        }
    }

    /**
     * @test
     *
     * @expectedException \Hexopia\Map\Exceptions\OnlyObjectsAllowedAsMapValue
     */
    public function apply_callback_needs_to_return_object_or_null()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);

        $map->apply(function ($hex, $unit) {
            return 0;
        });
    }
    
    /**
     * @test
     */
    public function clear_a_map()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);

        $map->clear();

        $this->assertTrue($map->isEmpty());
    }
    
    /**
     * @test
     */
    public function remove_a_object_from_a_hex()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);

        $this->assertEquals(
            $mapFields[1]->object,
            $map->remove(Hex::make(-1, 0))
        );

        $this->assertCount(1, $map->objects());

        $this->assertInstanceOf(SampleHeroObject::class, $map->objects()[$mapFields[2]->hex->hash()]);
    }
}