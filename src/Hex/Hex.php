<?php

namespace Hexopia\Hex;

use Hexopia\Hex\Helpers\HexDiagnoalDirections;
use Hexopia\Hex\Helpers\HexDirections;
use Hexopia\Hex\Types\HexEmpty;
use Hexopia\Hex\Types\HexTypes;

class Hex
{
    protected $q;
    protected $r;
    public $type;

    function __construct($q, $r, HexTypes $type = null)
    {
        if (!$type) {
            $type = new HexEmpty();
        }

        $this->q = $q;
        $this->r = $r;
        $this->type = $type;
    }

    public function s(): int
    {
        return -$this->q -$this->r;
    }

    public function equals(Hex $hex): bool
    {
        return $this->q == $hex->q
            && $this->r == $hex->r
            && $this->s() == $hex->s;
    }

    public static function add(Hex $a, Hex $b): Hex
    {
        return new Hex(
            $a->q + $b->q,
            $a->r + $b->r
        );
    }

    public static function subtract(Hex $a, Hex $b): Hex
    {
        return new Hex(
            $a->q - $b->q,
            $a->r - $b->r
        );
    }

    public static function multiply(Hex $a, int $multiplier): Hex
    {
        return new Hex(
            $a->q * $multiplier,
            $a->r * $multiplier
        );
    }

    public static function round(Hex $hex)
    {
                                    // 0.3
                                    // 0.4
                                    // -0.7

//        var_dump($hex->q, $hex->s(), $hex->r);
//        echo PHP_EOL;
//
//        $rx = round($hex->q);       // 0
//        $ry = round($hex->s());     // 0
//        $rz = round($hex->r);       // -1
//
//        var_dump($rx, $ry, $rz);
//        echo PHP_EOL;
//
//        $x_diff = abs($rx - $hex->q);   // 0.3
//        $y_diff = abs($ry - $hex->s()); // 0.4
//        $z_diff = abs($rz - $hex->r);   // 0.3
//
//        var_dump($x_diff, $y_diff, $z_diff);
//        echo PHP_EOL;
//
//        if ($x_diff > $y_diff && $x_diff > $z_diff) {
//            $rx = -$ry-$rz;
//        } else if ($y_diff > $z_diff) {
//            $ry = -$rx-$rz;
//        } else {
//            echo "WooooT" . PHP_EOL;
//            $rz = -$rx-$ry;
//        }
//
//        var_dump($rx, $ry, $rz);
//            echo PHP_EOL;
//        die();
//
//        return new Hex($rx, $rz);
    }
    
    public function length(): int
    {
        return (int) ((abs($this->q) + abs($this->r) + abs($this->s())) / 2);
    }

    public function distance(Hex $b): int
    {
        return self::subtract($this, $b)->length();
    }

    public function neighbor(int $neighbor): Hex
    {
        return self::add($this, HexDirections::hex($neighbor));
    }

    public function diagonalNeighbor(int $neighbor): Hex
    {
        return self::add($this, HexDiagnoalDirections::hex($neighbor));
    }

//    public function lerp($b, $t)
//    {
//        return new Hex(
//            $this->q * (1.0 - $t) + $b->q * $t,
//            $this->r * (1.0 - $t) + $b->r * $t
//        );
//    }

    function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->$name;
        }

        if (method_exists(self::class, $name)) {
            return $this->$name();
        }
    }
}