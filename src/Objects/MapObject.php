<?php

namespace Hexopia\Objects;

use \Hexopia\Contracts\MapObject as ObjectInterface;

abstract class MapObject implements ObjectInterface
{
    function hash()
    {
        return spl_object_hash($this);
    }

    /**
     * @param ObjectInterface $obj
     * @return bool
     */
    function equals($obj): bool
    {
        return $this->getType() === $obj->getType();
    }
 }
