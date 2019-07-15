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
}