<?php

namespace Hexopia\Map\ConsolePlotter\Frames;

use Hexopia\Hex\Hex;
use Hexopia\Map\Shapes\HexMap;


class HexFrame extends Frame
{
    private $rows;
    private $columns;
    private $centerRow;
    private $centerCol;

    function __construct(HexMap $map)
    {
        parent::__construct($map);

        $this->rows 		= $map->radius > 0 ? $map->radius *  8 + 5 : 5;
        $this->columns	    = $map->radius > 0 ? $map->radius * 14 + 9 : 9;
        $this->centerRow 	= ceil(($this->rows / 2));
        $this->centerCol	= ceil(($this->columns / 2));

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
            'col' => $this->centerCol + (7 * $hex->q) - 1,
            'row' => $this->centerRow + ($hex->r * 4) + $hex->q * 2 - 1
        ];
    }
}