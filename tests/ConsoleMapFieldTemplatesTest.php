<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Map\ConsolePlotter\Helpers\ConsoleHexTemplates;
use Hexopia\Map\MapField;
use Hexopia\Objects\Unit;
use Tests\Mocks\CustomConsoleHexTemplates;
use Tests\Mocks\FunctionalConsoleHexTemplates;
use Tests\Mocks\HexHeroWithName;

class ConsoleMapFieldTemplatesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function empty_hex()
    {
        $mapField = MapField::makeEmpty(0, 0);

        $drawing = ConsoleHexTemplates::forMapField($mapField);

        $this->assertEquals('.', $drawing[2][4]);
    }

    /**
     * @test
     */
    public function unit_hex()
    {
        $mapField = MapField::make(0, 0, new Unit());

        $drawing = ConsoleHexTemplates::forMapField($mapField);

        $this->assertEquals('H', $drawing[2][4]);
    }
    
    /**
     * @test
     */
    public function custom_hex()
    {
        $mapField = MapField::makeEmpty(0, 0);

        $drawing = CustomConsoleHexTemplates::forMapField($mapField);

        $this->assertEquals('x', $drawing[2][4]);
    }

    /**
     * test
     */
    public function custom_function_hex()
    {
        $hex = new Hex(
            0, 0,
            new HexHeroWithName('nanosch')
        );

        $drawing = FunctionalConsoleHexTemplates::forMapField($hex);

        $this->assertStringContainsString('nanosch', implode($drawing[2]));
    }
}