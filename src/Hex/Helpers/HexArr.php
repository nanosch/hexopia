<?php

namespace Hexopia\Hex\Helpers;

use Hexopia\Hex\Hex;

class HexArr
{
    public static function search(Hex $needle, $haystack)
    {
        foreach ($haystack as $key => $hexagon) {
            if ($needle->equals($hexagon)) {
                return $key;
            }
        }

        return false;
    }
}