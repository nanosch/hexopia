<?php

namespace Hexopia\Contracts;

use Ds\Hashable;

interface MapObject extends Hashable
{
    const UNIT = "unit";
    const OBSTACLE = "obstacle";
    const MARKER = "marker";

    public function getType();
}
