<?php

namespace Test;

use Hexopia\Contracts\MapObject;
use Hexopia\Map\Map;
use Hexopia\Map\MapField;
use Tests\Mocks\SampleHeroObject;
use Tests\Mocks\SampleMonsterObject;

class MapMorphedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function copy_a_map()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);
        $newMap = $map->copy();

        $this->assertEquals($map->fields(), $newMap->fields());

        $map->clear();

        $this->assertEquals(3, $newMap->count());
    }

    /**
     * @test
     */
    public function filter_a_map()
    {
        $mapFields[] = MapField::makeEmpty(0, 0);
        $mapFields[] = MapField::make(-1, 0, new SampleMonsterObject());
        $mapFields[] = MapField::make(0, 1, new SampleHeroObject());

        $map = new Map($mapFields);

        $unitMap = $map->filter(function (MapField $field){
            return $field->object && $field->object->getType() === MapObject::UNIT;
        });

        $this->assertEquals(3, $map->count());
        $this->assertEquals(2, $unitMap->count());
        $this->assertEquals([
            $mapFields[1]->hex->hash() => $mapFields[1],
            $mapFields[2]->hex->hash() => $mapFields[2]
        ], $unitMap->fields());
    }
}
