<?php

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHero;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Map\ConsolePlotter\MapPlotter;

require __DIR__.'/../vendor/autoload.php';

$map = \Hexopia\Map\Shapes\HexMap::hex(5);

$movement = readline('Number of Moves:');

$obstacles = createObstacles();

$map->placeMany($obstacles);

$reachable = $map->reachable($movement);

foreach ($reachable as $highlight) {
    $highlight->type = new HexHighlighted();
}

$map->placeMany($reachable);

$map->place(
    new Hex(0,0, new HexHero())
);

$plotter = MapPlotter::draw($map);

echo PHP_EOL;

$plotter->plot();

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
