<?php

namespace Tests\Mocks;

use Hexopia\Objects\Unit;

class HexHeroWithName extends Unit
{
    public $name;

    public function __construct($name = null)
    {
        $this->name = $name;
    }
}