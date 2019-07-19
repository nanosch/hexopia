<?php

namespace Hexopia\Map\ConsolePlotter\Helpers;

use Hexopia\Contracts\MapObject;
use Hexopia\Map\MapField;
use Hexopia\Objects\Marker;

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
        ['/', ' ', ' ', ' ', '×', ' ', ' ', ' ', '\\'],
        ['\\', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '/'],
        [' ', '\\', '_', '_', '_', '_', '_', '/', ' '],
    ];

    protected static $obstacle = [
        [' ', ' ', "_", '_', '_', '_', '_', ' ', ' '],
        [' ', '/', ' ', ' ', ' ', ' ', ' ', '\\', ' '],
        ['/', ' ', ' ', ' ', "\033[1m\033[31mØ\033[0m\033[0m", ' ', ' ', ' ', '\\'],
        ['\\', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '/'],
        [' ', '\\', '_', '_', '_', '_', '_', '/', ' '],
    ];

    protected static function highlighted(Marker $marker)
    {
        $hexTemplate = static::$highlighted;
        $hexTemplate[2][4] = str_replace(
            '%color%', $marker->colorCode(), "\033[1m\033[%color%m×\033[0m\033[0m"
        );

        return $hexTemplate;
    }

    public static function forMapField(MapField $mapField)
    {
        if ( ! $mapField->object) {
            return static::$empty;
        }

        switch ($mapField->object->getType()) {
            case MapObject::UNIT:
                return static::$hero;
                break;
            case MapObject::MARKER:
                return static::highlighted($mapField->object);
                break;
            case MapObject::OBSTACLE:
                return static::$obstacle;
                break;
            default:
                return static::$empty;
                break;
        }
    }
}
