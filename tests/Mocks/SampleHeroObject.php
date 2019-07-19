<?php

namespace Tests\Mocks;

use Ds\An;
use Hexopia\Contracts\MapObject;

class SampleHeroObject implements MapObject
{
    public $live = 99;

    function hash()
    {
        return get_class($this);
    }

    function equals($obj): bool
    {
        return true;
    }

    public function getType()
    {
        return MapObject::UNIT;
    }
}
