<?php

namespace Test;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Map\Plotter\Helpers\ConsoleHexTemplates;

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
}