<?php

namespace Hexopia\Hex\Types;

class HexHero extends HexTypes
{
    public static $value;

    function __construct()
    {
        $this->value = self::HERO;
    }
}