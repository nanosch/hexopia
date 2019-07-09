<?php

namespace Hexopia\Hex\Types;

abstract class HexTypes
{
    const EMPTY = 'empty';
    const HERO  = 'hero';
    const HIGHLIGHTED  = 'Highlighted';
    const OBSTACLE  = 'Obstacle';

    public $value;
}