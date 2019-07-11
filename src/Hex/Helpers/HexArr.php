<?php

namespace Hexopia\Hex\Helpers;

use Hexopia\Hex\Hex;

class HexArr
{
    public static function search(Hex $needle = null, $haystack)
    {
        if (!$needle) {
            return false;
        }

        foreach ($haystack as $key => $hexagon) {
            if ($needle->equals($hexagon)) {
                return $key;
            }
        }

        return false;
    }
}