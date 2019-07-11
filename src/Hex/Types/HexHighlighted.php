<?php

namespace Hexopia\Hex\Types;

class HexHighlighted extends HexTypes
{
    public $value;
    public $colorCode;

    function __construct($colorCode = null)
    {
        $this->value = self::HIGHLIGHTED;
        $this->colorCode = $colorCode ? $colorCode : 32;
    }
}