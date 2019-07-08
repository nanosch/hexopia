<?php

namespace Hexopia\Hex\Helpers;

use Hexopia\Hex\Hex;

class HexDirections
{
    protected static $directions = [
        [1, 0], [1, -1], [0, -1],
        [-1, 0], [-1, 1], [0, 1]
    ];

    public static function hex($direction)
    {
        return new Hex(
            self::$directions[$direction][0],
            self::$directions[$direction][1]
        );
    }
}