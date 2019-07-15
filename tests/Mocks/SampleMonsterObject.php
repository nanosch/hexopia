<?php

namespace Tests\Mocks;

use Ds\An;
use Hexopia\Contracts\Object;

class SampleMonsterObject implements Object
{
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