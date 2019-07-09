<?php

use Hexopia\Hex\Hex;

require __DIR__.'/../vendor/autoload.php';

$map = \Hexopia\Map\Shapes\HexMap::hex(3);

$start = new Hex(-2, 0);
$target = new Hex(3, -1);

$map->drawLine($start, $target);

$screen = \Hexopia\Map\ConsolePlotter\MapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;
