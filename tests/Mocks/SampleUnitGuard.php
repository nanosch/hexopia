<?php

namespace Tests\Mocks;

use Hexopia\Contracts\MapGuard;
use Hexopia\Contracts\Object;
use Hexopia\Map\MapField;

class SampleUnitGuard implements MapGuard
{
    public function allow(MapField $fieldInMap, MapField $new): bool
    {
        if ( ! $fieldInMap->object) {
            return true;
        }

        return $fieldInMap->object->getType() !== Object::UNIT;
    }
}