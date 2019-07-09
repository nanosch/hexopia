<?php

require __DIR__.'/../vendor/autoload.php';

$rings = readline('Number of Rings:');

$map = \Hexopia\Map\Shapes\HexMap::hex($rings);

$screen = \Hexopia\Map\ConsolePlotter\MapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;
