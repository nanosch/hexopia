<?php

namespace Hexopia\Objects;

use \Hexopia\Contracts\Object as ObjectInterface;

abstract class Object implements ObjectInterface
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