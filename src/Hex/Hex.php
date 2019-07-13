<?php

namespace Hexopia\Hex;

use Ds\Hashable;
use Hexopia\Hex\Helpers\HexDiagnoalDirections;
use Hexopia\Hex\Helpers\HexDirections;

class Hex implements Hashable
{
    protected $q;
    protected $r;

    function __construct($q, $r)
    {
        $this->q = $q;
        $this->r = $r;
    }

    public function s()
    {
        return -$this->q -$this->r;
    }

    public function equals($hex): bool
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
    
    public function linedraw(Hex $target)
    {
        $N = $this->distance($target);

        $self_nudge = new Hex($this->q + 0.000001, $this->r + 0.000001);
        $target_nudge = new Hex($target->q + 0.000001, $target->r + 0.000001);

        $line = [];
        $step = 1.0 / max($N, 1);

        for ($i = 0; $i <= $N; $i++) {
            $line[] = Hex::round(
                $self_nudge->lerp($target_nudge, $step * $i)
            );
        }

        return $line;
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

    function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->$name;
        }

        if (method_exists(self::class, $name)) {
            return $this->$name();
        }
    }

    function hash()
    {
        return spl_object_hash($this);
    }
}