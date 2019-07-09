<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexEmpty;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Map\ConsolePlotter\Helpers\ConsoleHexTemplates;
use Tests\Mocks\CustomConsoleHexTemplates;
use Tests\Mocks\FunctionalConsoleHexTemplates;
use Tests\Mocks\HexHeroWithName;

class ConsoleHexTemplatesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function empty_hex()
    {
        $hex = new Hex(0, 0);

        $drawing = ConsoleHexTemplates::forHex($hex);

        $this->assertEquals('.', $drawing[2][4]);
    }

    /**
     * @test
     */
    public function hero_hex()
    {
        $hex = new Hex(
            0, 0,
            new HexHero()
        );

        $drawing = ConsoleHexTemplates::forHex($hex);

        $this->assertEquals('H', $drawing[2][4]);
    }
    
    /**
     * @test
     */
    public function custom_hex()
    {
        $hex = new Hex(
            0, 0,
            new HexEmpty()
        );

        $drawing = CustomConsoleHexTemplates::forHex($hex);

        $this->assertEquals('x', $drawing[2][4]);
    }

    /**
     * @test
     */
    public function custom_function_hex()
    {
        $hex = new Hex(
            0, 0,
            new HexHeroWithName('nanosch')
        );

        $drawing = FunctionalConsoleHexTemplates::forHex($hex);

        $this->assertStringContainsString('nanosch', implode($drawing[2]));
    }
}