<?php

namespace Tests\Mocks;

use Ds\An;
use Hexopia\Contracts\Object;

class SampleHeroObject implements Object
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
        return Object::UNIT;
    }
}