<?php

namespace Hexopia\Map\ConsolePlotter\Frames;

use Hexopia\Hex\Hex;
use Hexopia\Map\Shapes\TriangleMap;


class TriangeFrame extends Frame
{
    private $rows;
    private $columns;

    function __construct(TriangleMap $map)
    {
        parent::__construct($map);

        $this->rows 		= $map->size > 0 ? $map->size * 4 + 5 : 5;
        $this->columns	    = $map->size > 0 ? $map->size * 7 + 9 : 9;

        $this->display 		= array_fill(0, $this->rows, ' ');

        for ($i = 0; $i < $this->rows; $i++) {
            $this->display[$i] = array_fill(0, $this->columns, ' ');
        }
    }

    public function place(Hex $hex)
    {
        $coords = $this->XyForHex($hex);

        $consoleHex = $this->templates::forHex($hex);

        for ($y = $coords['row'] - 2, $hy = 0; $hy < count($consoleHex); $y++, $hy++) {
            for ($x = $coords['col'] - 4, $hx = 0; $hx < count($consoleHex[0]); $x++, $hx++) {
                if($this->display[$y][$x] == ' '){
                    $this->display[$y][$x] = $consoleHex[$hy][$hx];
                }
            }
        }
    }

    public function XyForHex(Hex $hex)
    {
        return [
            'col' => 5 + (7 * $hex->q) - 1,
            'row' => 3 + ($hex->r * 4) + $hex->q * 2 - 1
        ];
    }
}