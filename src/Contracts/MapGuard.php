<?php

namespace Hexopia\Contracts;

use Hexopia\Map\MapField;

interface MapGuard
{
    public function allow(MapField $fieldInMap, MapField $new): bool;
}