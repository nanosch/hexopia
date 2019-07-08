<?php

require __DIR__.'/../vendor/autoload.php';

$size = readline('Triangle Size:');

$map = \Hexopia\Map\Map::triangle($size);

$screen = \Hexopia\Map\Plotter\ConsoleMapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;
