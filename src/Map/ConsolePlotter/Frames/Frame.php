<?php

namespace Hexopia\Map\ConsolePlotter\Frames;

use Hexopia\Hex\Hex;
use Hexopia\Map\Map;
use Hexopia\Map\ConsolePlotter\Helpers\ConsoleHexTemplates;
use Hexopia\Map\MapField;

abstract class Frame
{
    protected $map;
    protected $display;
    protected $templates;

    function __construct(Map $map, $templates = null)
    {
        $this->map = $map;
        $this->display = [[]];

        $this->templates = $templates ? get_class($templates) : ConsoleHexTemplates::class;
    }

    abstract public function place(MapField $mapField);
    
    public function setTemplates(ConsoleHexTemplates $templates)
    {
        $this->templates = get_class($templates);

        return $this;
    }

    public function render() {
        foreach ($this->map->fields() as $hexagon) {
            $this->place($hexagon);
        }

        return $this;
    }

    function __get($name)
    {
        if (property_exists(static::class, $name)) {
            return $this->$name;
        }

        if (method_exists(static::class, $name)) {
            return $this->$name();
        }
    }
}