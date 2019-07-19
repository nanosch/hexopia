<?php

namespace Hexopia\Objects;

class Marker extends MapObject
{
    protected $colorCode;

    public function __construct($colorCode = 32)
    {
        $this->colorCode = $colorCode;
    }

    public function colorCode()
    {
        return $this->colorCode;
    }

    public function getType()
    {
        return MapObject::MARKER;
    }
}
