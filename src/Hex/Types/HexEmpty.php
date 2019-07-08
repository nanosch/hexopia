<?php

namespace Hexopia\Hex\Types;

class HexEmpty extends HexTypes
{
    public static $value = self::EMPTY;

    function __construct()
    {
        $this->value = self::EMPTY;
    }
}