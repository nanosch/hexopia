<?php

namespace Test;

use Hexopia\Hex\Helpers\HexDirections;
use Hexopia\Hex\Hex;

class HexDirectionsTest extends \PHPUnit\Framework\TestCase
{
    public function hexProvider()
    {
        return [
            [0, 1, 0], [1, 1, -1], [2, 0, -1],
            [3, -1, 0], [4, -1, 1], [5, 0, 1]
        ];
    }

    /**
     * @test
     *
     * @dataProvider hexProvider
     */
    public function corresponding_direction_for_integer($int, $q, $r)
    {
        $hex = new Hex($q, $r);
        $dirHex = HexDirections::hex($int);

        $this->assertTrue($hex->equals($dirHex));
    }
}