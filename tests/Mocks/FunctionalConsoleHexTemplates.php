<?php

namespace Tests\Mocks;

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexTypes;

class FunctionalConsoleHexTemplates extends \Hexopia\Map\Plotter\Helpers\ConsoleHexTemplates
{
    public static function hero($hex)
    {
        $drawing = static::$hero;
        $start   = strlen($hex->type->name) < 6 ? ceil((8 - strlen($hex->type->name))  / 2) : 1;

        for ($i = 0; $i < strlen($hex->type->name); $i++) {
            if ($key > 7) {
                break;
            }

            $drawing[2][$i + $start] = $hex->type->name[$i];
        }

        return $drawing;
    }

    public static function forHex(Hex $hex)
    {
        switch ($hex->type->value) {
            case HexTypes::HERO:
                return static::hero($hex);
                break;
            default:
                return static::$empty;
                break;
        }
    }
}