<?php

namespace Hexopia\Map\ConsolePlotter\Helpers;

use Hexopia\Hex\Types\HexTypes;
use Hexopia\Hex\Hex;

class ConsoleHexTemplates
{
    protected static $empty = [
        [' ', ' ', '_', '_', '_', '_', '_', ' ', ' '],
        [' ', '/', ' ', ' ', ' ', ' ', ' ', '\\', ' '],
        ['/', ' ', ' ', ' ', '.', ' ', ' ', ' ', '\\'],
        ['\\', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '/'],
        [' ', '\\', '_', '_', '_', '_', '_', '/', ' '],
    ];

    protected static $hero = [
        [' ', ' ', '_', '_', '_', '_', '_', ' ', ' '],
        [' ', '/', ' ', ' ', ' ', ' ', ' ', '\\', ' '],
        ['/', ' ', ' ', ' ', 'H', ' ', ' ', ' ', '\\'],
        ['\\', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '/'],
        [' ', '\\', '_', '_', '_', '_', '_', '/', ' '],
    ];

    protected static $highlighted = [
        [' ', ' ', "_", '_', '_', '_', '_', ' ', ' '],
        [' ', '/', ' ', ' ', ' ', ' ', ' ', '\\', ' '],
        ['/', ' ', ' ', ' ', "\033[1m\033[33m×\033[0m\033[0m", ' ', ' ', ' ', '\\'],
        ['\\', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '/'],
        [' ', '\\', '_', '_', '_', '_', '_', '/', ' '],
    ];

    public static function forHex(Hex $hex)
    {
        switch ($hex->type->value) {
            case HexTypes::HERO:
                return static::$hero;
                break;
            case HexTypes::HIGHLIGHTED:
                return static::$highlighted;
                break;
            default:
                return static::$empty;
                break;
        }
    }
}