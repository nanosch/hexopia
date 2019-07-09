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

    public function s()
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
        $qi = round($hex->q);
        $ri = round($hex->r);
        $si = round($hex->s());

        $q_diff = abs($qi - $hex->q);
        $r_diff = abs($ri - $hex->r);
        $s_diff = abs($si - $hex->s());

        if ($q_diff > $r_diff && $q_diff > $s_diff) {
            $qi = -$ri -$si;
        }
        else if ($r_diff > $s_diff) {
            $ri = -$qi -$si;
        }

        return new Hex($qi, $ri);
    }
    
    public function lerp(Hex $pos, $t)
    {
        return new Hex(
            $this->q * (1.0 - $t) + $pos->q * $t,
            $this->r * (1.0 - $t) + $pos->r * $t
        );
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