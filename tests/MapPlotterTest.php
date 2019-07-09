<?php

namespace Test;

use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Hex;
use Hexopia\Map\ConsolePlotter\MapPlotter;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Map\Shapes\TriangleMap;
use Tests\Mocks\CustomConsoleHexTemplates;
use Tests\Mocks\FunctionalConsoleHexTemplates;
use Tests\Mocks\HexHeroWithName;

class MapPlotterTest extends \PHPUnit\Framework\TestCase
{
    public function hexMapAssertions()
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
     * @dataProvider hexMapAssertions
     */
    public function draw_empty_hex_map($radius, $anzHex)
    {
        $emptyFields = 0;
        $map = HexMap::hex($radius);

        $screenMap = MapPlotter::draw($map);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->frame->display as $rows) {
            foreach ($rows as $char) {
                if ($char == '.') {
                    $emptyFields++;
                }
            }
        }

        $this->assertEquals($anzHex, $emptyFields);
    }

    public function triangleMapAssertions()
    {
        return [
            [0, 1], [1, 3],
            [2, 6], [3, 10]
        ];
    }

    /**
     * @test
     *
     * @dataProvider triangleMapAssertions
     */
    public function draw_empty_triangle_map($size, $anzHex)
    {
        $emptyFields = 0;
        $map = TriangleMap::triangle($size);

        $screenMap = MapPlotter::draw($map);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->frame->display as $rows) {
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
     */
    public function draw_hero_in_map()
    {
        $emptyFields = 0;
        $heros = 0;
        $map = HexMap::hex(1);

        $hero = new Hex(1, -1, new HexHero());

        $map->place($hero);

        $screenMap = MapPlotter::draw($map);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->frame->display as $rows) {
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
        $map = HexMap::hex(1);

        $template = new CustomConsoleHexTemplates();

        $screenMap = MapPlotter::draw($map, $template);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->frame->display as $rows) {
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
        $map = HexMap::hex(1);

        $template = new FunctionalConsoleHexTemplates();

        $hero = new Hex(
            0, 0,
            new HexHeroWithName('nanosch')
        );

        $map->place($hero);

        $screenMap = MapPlotter::draw($map, $template);

        echo PHP_EOL;

        $screenMap->plot();

        echo PHP_EOL;

        foreach ($screenMap->frame->display as $row) {

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