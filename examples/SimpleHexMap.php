<?php

require __DIR__.'/../vendor/autoload.php';

$rings = readline('Number of Rings:');

$map = \Hexopia\Map\Map::hex($rings);

$screen = \Hexopia\Map\Plotter\ConsoleMapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;
