<?php

use Hexopia\Map\ConsolePlotter\MapPlotter;
use Hexopia\Map\MapField;
use Hexopia\Objects\Marker;
use Hexopia\Objects\Obstacle;

require __DIR__.'/../vendor/autoload.php';

$map = \Hexopia\Map\Shapes\HexMap::hex(4);

$obstacles = createObstacles();

$map->putAll($obstacles);

$plotter = MapPlotter::draw($map);

$plotter->plot();

do {
    $sq = readline('Start Q: ');
    $sr = readline('Start R: ');
    $start = MapField::make($sq, $sr, new Marker());

    $needNew = \Hexopia\Hex\Helpers\HexArr::searchMapField($start, $obstacles);

    if ($needNew) {
        echo "point is obstacle". PHP_EOL;
    }

} while($needNew);

do {
    $tq = readline('Target Q: ');
    $tr = readline('Target R: ');
    $target = MapField::make($tq, $tr, new Marker(33));

    $needNew = \Hexopia\Hex\Helpers\HexArr::searchMapField($target, $obstacles) || $target->equalField($start);

    if ($needNew) {
        echo "point is obstacle or start" . PHP_EOL;
    }

} while($needNew);

$map->putAll(
    [$start, $target]
);

foreach ($map->pathBetween($start->hex, $target->hex) as $step) {
    $step->object = new Marker(34);

    $plotter = MapPlotter::draw($map);

    system('clear');

    $plotter->plot();

    sleep(1);
}


function createObstacles() {
    $obstacles[] = MapField::make(
        1, -1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        2, -1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        2, 0, new Obstacle()
    );

    $obstacles[] = MapField::make(
        2, 1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        1, 2, new Obstacle()
    );

    $obstacles[] = MapField::make(
        0, 2, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -1, 2, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -1, 1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -2, 1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -3, 2, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -4, 3, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -5, 4, new Obstacle()
    );

    $obstacles[] = MapField::make(
        -1, -1, new Obstacle()
    );

    $obstacles[] = MapField::make(
        0, -2, new Obstacle()
    );

    $obstacles[] = MapField::make(
        1, -3, new Obstacle()
    );

    return $obstacles;
}