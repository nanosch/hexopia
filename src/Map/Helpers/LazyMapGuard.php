<?php

namespace Hexopia\Map\Helpers;

use Hexopia\Contracts\MapGuard;
use Hexopia\Map\MapField;

class LazyMapGuard implements MapGuard
{
    public function guard(MapField $fieldInMap, MapField $new): bool
    {
        return true;
    }
}