<?php

namespace Hexopia\Hex\Types;

class HexObstacle extends HexTypes
{
    public $value;

    function __construct()
    {
        $this->value = self::OBSTACLE;
    }
}