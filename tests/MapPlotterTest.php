<?php

namespace Test;

use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\Plotter\ConsoleMapPlotter;
use Tests\Mocks\CustomConsoleHexTemplates;
use Tests\Mocks\FunctionalConsoleHexTemplates;
use Tests\Mocks\HexHeroWithName;

class MapPlotterTest extends \PHPUnit\Framework\TestCase
{
    public function mapAssertions()
    {
        return [
            [0, 1], [1, 7],
            [2, 19], [3, 37]
        ];
    }

    /**
     * @test
     * An Empty Map is
     *
     * @dataProvider mapAssertions
     */
    public function draw_empty_map($radius, $anzHex)
    {
        $emptyFields = 0;
        $map = Map::hex($radius);

        $screenMap = ConsoleMapPlotter::draw($map);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->screen as $rows) {
            foreach ($rows as $char) {
                if ($char == '.') {
                    $emptyFields++;
                }
            }
        }

        $this->assertEquals($anzHex, $emptyFields);
    }

    /**
     * @test
     * An Empty Map is
     *
     */
    public function draw_hero_in_map()
    {
        $emptyFields = 0;
        $heros = 0;
        $map = Map::hex(1);

        $hero = new Hex(1, -1, new HexHero());

        $map->place($hero);

        $screenMap = ConsoleMapPlotter::draw($map);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->screen as $rows) {
            foreach ($rows as $char) {
                if ($char == '.') {
                    $emptyFields++;
                } else if($char == 'H') {
                    $heros++;
                }
            }
        }

        $this->assertEquals(6, $emptyFields);
        $this->assertEquals(1, $heros);
    }
    
    /**
     * @test
     */
    public function custom_templates()
    {
        $emptyFields = 0;
        $map = Map::hex(1);

        $template = new CustomConsoleHexTemplates();

        $screenMap = ConsoleMapPlotter::draw($map, $template);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->screen as $rows) {
            foreach ($rows as $char) {
                if ($char == 'x') {
                    $emptyFields++;
                }
            }
        }

        $this->assertEquals(7, $emptyFields);
    }

    /**
     * @test
     */
    public function custom_function_templates()
    {
        $emptyFields = 0;
        $heros = 0;
        $map = Map::hex(1);

        $template = new FunctionalConsoleHexTemplates();

        $hero = new Hex(
            0, 0,
            new HexHeroWithName('nanosch')
        );

        $map->place($hero);

        $screenMap = ConsoleMapPlotter::draw($map, $template);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->screen as $row) {

            if (strpos(implode($row), 'nanosch')) {
                $heros++;
            }

            foreach ($row as $char) {
                if ($char == '.') {
                    $emptyFields++;
                }
            }
        }

        $this->assertEquals(1, $heros);
        $this->assertEquals(6, $emptyFields);
    }
}