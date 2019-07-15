<?php

namespace Hexopia\Contracts;

use Hexopia\Map\MapField;

interface MapGuard
{
    public function guard(MapField $fieldInMap, MapField $new): bool;
}