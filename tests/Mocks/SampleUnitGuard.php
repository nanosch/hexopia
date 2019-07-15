<?php

namespace Tests\Mocks;

use Hexopia\Contracts\MapGuard;
use Hexopia\Contracts\Object;
use Hexopia\Map\MapField;

class SampleUnitGuard implements MapGuard
{
    public function guard(MapField $fieldInMap, MapField $new): bool
    {
        return $fieldInMap->object->getType() !== Object::UNIT;
    }
}