<?php

namespace Hexopia\Objects;

class Obstacle extends MapObject
{

    public function getType()
    {
        return MapObject::OBSTACLE;
    }
}
