<?php

namespace Tests\Mocks;

use Hexopia\Contracts\MapObject;
use Hexopia\Map\MapField;

class FunctionalConsoleHexTemplates extends \Hexopia\Map\ConsolePlotter\Helpers\ConsoleHexTemplates
{
    public static function hero($object)
    {
        $drawing = static::$hero;
        $start   = strlen($object->name) < 6 ? ceil((8 - strlen($object->name))  / 2) : 1;

        for ($i = 0; $i < strlen($object->name); $i++) {
            if ($i > 7) {
                break;
            }

            $drawing[2][$i + $start] = $object->name[$i];
        }

        return $drawing;
    }

    public static function forMapField(MapField $mapField)
    {
        if ( ! $mapField->object) {
            return static::$empty;
        }

        switch ($mapField->object->getType()) {
            case MapObject::UNIT:
                return static::hero($mapField->object);
                break;
            default:
                return static::$empty;
                break;
        }
    }
}
