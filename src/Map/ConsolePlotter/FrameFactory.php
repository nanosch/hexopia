<?php

namespace Hexopia\Map\ConsolePlotter;

use Hexopia\Map\Map;
use Hexopia\Map\ConsolePlotter\Frames\HexFrame;
use Hexopia\Map\ConsolePlotter\Frames\TriangeFrame;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Map\Shapes\TriangleMap;

class FrameFactory
{
    public static function make(Map $map)
    {
        switch (get_class($map)) {
            case HexMap::class:
                return new HexFrame($map);
            case TriangleMap::class:
                return new TriangeFrame($map);
        }
    }
}