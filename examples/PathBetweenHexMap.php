<?php

use Hexopia\Hex\Hex;
use Hexopia\Hex\Types\HexHighlighted;
use Hexopia\Map\ConsolePlotter\MapPlotter;

require __DIR__.'/../vendor/autoload.php';

$map = \Hexopia\Map\Shapes\HexMap::hex(4);

$obstacles = createObstacles();

$map->placeMany($obstacles);

$start = new Hex(0, 1, new HexHighlighted());

$target = new Hex(-4, 2, new HexHighlighted(33));

$map->placeMany(
    [$start, $target]
);

$map->pathBetween($start, $target);

//$plotter = MapPlotter::draw($map);
//
//echo PHP_EOL;
//
//$plotter->plot();
//
//echo PHP_EOL;


//do {
//    $sq = readline('Start Q: ');
//    $sr = readline('Start R: ');
//    $start = new Hex($sq, $sr, new HexHighlighted());
//
//    $needNew = \Hexopia\Hex\Helpers\HexArr::search($start, $obstacles);
//
//    if ($needNew) {
//        echo "point is obstacle". PHP_EOL;
//    }
//
//} while($needNew);
//
//do {
//    $tq = readline('Target Q: ');
//    $tr = readline('Target R: ');
//    $target = new Hex($tq, $tr, new HexHighlighted(33));
//
//    $needNew = \Hexopia\Hex\Helpers\HexArr::search($target, $obstacles) || $target->equals($start);
//
//    if ($needNew) {
//        echo "point is obstacle or start" . PHP_EOL;
//    }
//
//} while($needNew);


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
