<?php

namespace Hexopia\Hex\Types;

class HexHero extends HexTypes
{
    public $value;

    function __construct()
    {
        $this->value = self::HERO;
    }
}