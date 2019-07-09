<?php

namespace Hexopia\Map\ConsolePlotter;

use Hexopia\Map\Map;
use Hexopia\Map\ConsolePlotter\Helpers\ConsoleHexTemplates;

class MapPlotter
{
    protected $map;
    protected $frame = [[]];
    protected $rows;
    protected $columns;
    protected $centerRow;
    protected $centerCol;


    function __construct(Map $map, ConsoleHexTemplates $templates = null)
    {
        $this->map = $map;
        $this->frame = FrameFactory::make($map);

        if ($templates) {
            $this->frame->setTemplates($templates);
        }
    }

    public function plot()
    {
        $this->frame->render();

        foreach ($this->frame->display as $key => $line) {

            printf("%3s" . " ", $key);

            foreach ($line as $char) {
                printf("%1s", $char);
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }

    public static function draw(Map $map = null, ConsoleHexTemplates $templates = null)
    {
        return new static($map, $templates);
    }

    function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->$name;
        }
    }
}