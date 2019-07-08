<?php

namespace Hexopia\Map\Plotter;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\Plotter\Helpers\ConsoleHexTemplates;

class ConsoleMapPlotter
{
    protected $map;
    protected $templates;
    protected $screen = [[]];
    protected $rows;
    protected $columns;
    protected $centerRow;
    protected $centerCol;


    function __construct(Map $map, ConsoleHexTemplates $templates = null)
    {
        $this->map = $map;
        $this->templates = $templates ? get_class($templates) : ConsoleHexTemplates::class;

        $this->rows 		= $map->radius > 0 ? $map->radius *  8 + 5 : 5;
        $this->columns	    = $map->radius > 0 ? $map->radius * 14 + 9 : 9;
        $this->centerRow 	= ceil(($this->rows / 2));
        $this->centerCol	= ceil(($this->columns / 2));

        $this->screen 		= array_fill(0, $this->rows, ' ');

        for ($i = 0; $i < $this->rows; $i++) {
            $this->screen[$i] = array_fill(0, $this->columns, ' ');
        }
    }

    public function plot()
    {
        foreach ($this->screen as $key => $line) {

            printf("%3s" . " ", $key);

            foreach ($line as $char) {
                printf("%1s", $char);
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }

    public static function draw(Map $map = null, ConsoleHexTemplates $templates = null)
    {
        $plotter = new self($map, $templates);

        foreach ($plotter->map->hexagons as $hex) {
            $plotter->place($hex);
        }

        return $plotter;
    }

    protected function place(Hex $hex)
    {
        $coords = $this->XyForHex($hex);

        $consoleHex = $this->templates::forHex($hex);

        for ($y = $coords['row'] - 2, $hy = 0; $hy < count($consoleHex); $y++, $hy++) {
            for ($x = $coords['col'] - 4, $hx = 0; $hx < count($consoleHex[0]); $x++, $hx++) {
                if($this->screen[$y][$x] == ' '){
                    $this->screen[$y][$x] = $consoleHex[$hy][$hx];
                }
            }
        }

        return $this;
    }
    
    public function XyForHex(Hex $hex)
    {
        return [
            'col' => $this->centerCol + (7 * $hex->q) - 1,
            'row' => $this->centerRow + ($hex->r * 4) + $hex->q * 2 - 1
        ];
    }

    function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->$name;
        }
    }
}