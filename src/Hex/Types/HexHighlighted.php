<?php

namespace Hexopia\Hex\Types;

class HexHighlighted extends HexTypes
{
    public $value;

    function __construct()
    {
        $this->value = self::HIGHLIGHTED;
    }
}