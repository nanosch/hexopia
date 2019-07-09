<?php

require __DIR__.'/../vendor/autoload.php';

$size = readline('Triangle Size:');

$map = \Hexopia\Map\Shapes\TriangleMap::triangle($size);

$screen = \Hexopia\Map\ConsolePlotter\MapPlotter::draw($map);

$screen->plot();

echo PHP_EOL;
