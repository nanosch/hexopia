<?php

require __DIR__.'/../vendor/autoload.php';

$map = \Hexopia\Map\Shapes\HexMap::hex(5);

$obstacles = createObstacles();

$map->placeMany($obstacles);

$screen = \Hexopia\Map\ConsolePlotter\MapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;

function createObstacles() {
    $obstacles[] = new \Hexopia\Hex\Hex(
        1, -1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        2, -1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        2, 0, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        2, 1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        1, 2, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        0, 2, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -1, 2, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -1, 1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -2, 1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -3, 2, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -4, 3, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -5, 4, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        -1, -1, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        0, -2, new \Hexopia\Hex\Types\HexObstacle()
    );

    $obstacles[] = new \Hexopia\Hex\Hex(
        1, -3, new \Hexopia\Hex\Types\HexObstacle()
    );

    return $obstacles;
}
