<?php

namespace Hexopia\Hex\Helpers;

use Hexopia\Hex\Hex;

class HexDiagnoalDirections
{
    protected static $directions = [
        [+2, -1], [+1, -2], [-1, -1],
        [-2, +1], [-1, +2], [+1, +1]
    ];

    public static function hex($direction)
    {
        return new Hex(
            self::$directions[$direction][0],
            self::$directions[$direction][1]
        );
    }
}