<?php

namespace Tests\Mocks;

use Hexopia\Hex\Types\HexHero;

class HexHeroWithName extends HexHero
{
    public $name;

    public function __construct($name = null)
    {
        parent::__construct();
        $this->name = $name;
    }
}