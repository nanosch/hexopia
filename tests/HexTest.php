<?php

namespace Test;

use Hexopia\Hex\Hex;

class HexTest extends \PHPUnit\Framework\TestCase
{

    public function hexProvider()
    {
        return [
            [0, 0, 0],
            [0, -1, 1],
            [-2, 0, 2],
            [-3, 1, 2],
        ];
    }
    
    /**
     * @test
     * 
     * @dataProvider hexProvider
     */
    public function create_a_hex($q, $r, $s)
    {
        $hex = new Hex($q, $r);

        $this->assertEquals($hex->q, $q);
        $this->assertEquals($hex->r, $r);
        $this->assertEquals($hex->s, $s);
    }

    /**
     * @test
     *
     * @dataProvider hexProvider
     */
    public function create_a_hex_static($q, $r, $s)
    {
        $hex = Hex::make($q, $r);

        $this->assertEquals($hex->q, $q);
        $this->assertEquals($hex->r, $r);
        $this->assertEquals($hex->s, $s);
    }
    
    /**
     * @test
     */
    public function hex_equality_check()
    {
        $hex1 = new Hex(0, 0);
        $hex2 = new Hex(1, 1);
        $hex3 = new Hex(0, 0);

        $this->assertTrue($hex1->equals($hex3));
        $this->assertFalse($hex2->equals($hex1));
    }
    
    /**
     * @test
     */
    public function add_hex()
    {
        $hex1 = new Hex(-2, 1);
        $hex2 = new Hex(1, 1);

        $addedHex = Hex::add($hex1, $hex2);

        $this->assertEquals(Hex::class, get_class($addedHex));

        $this->assertTrue($hex1->q == -2);
        $this->assertTrue($hex1->r == 1);
        $this->assertTrue($hex1->s == 1);

        $this->assertTrue($hex2->q == 1);
        $this->assertTrue($hex2->r == 1);
        $this->assertTrue($hex2->s == -2);

        $this->assertTrue($addedHex->q == -1);
        $this->assertTrue($addedHex->r == 2);
        $this->assertTrue($addedHex->s == -1);

    }
    
    /**
     * @test
     */
    public function subtract_hex()
    {
        $hex1 = new Hex(-2, 1);
        $hex2 = new Hex(1, 1);

        $subHex = Hex::subtract($hex1, $hex2);

        $this->assertEquals(Hex::class, get_class($subHex));

        $this->assertTrue($hex1->q == -2);
        $this->assertTrue($hex1->r == 1);
        $this->assertTrue($hex1->s == 1);

        $this->assertTrue($hex2->q == 1);
        $this->assertTrue($hex2->r == 1);
        $this->assertTrue($hex2->s == -2);

        $this->assertTrue($subHex->q == -3);
        $this->assertTrue($subHex->r == 0);
        $this->assertTrue($subHex->s == 3);
    }

    /**
     * @test
     */
    public function multiply_hex()
    {
        $hex1 = new Hex(-2, 1);

        $multiHex = Hex::multiply($hex1, 3);

        $this->assertEquals(Hex::class, get_class($multiHex));

        $this->assertTrue($hex1->q == -2);
        $this->assertTrue($hex1->r == 1);
        $this->assertTrue($hex1->s == 1);

        $this->assertTrue($multiHex->q == -6);
        $this->assertTrue($multiHex->r == 3);
        $this->assertTrue($multiHex->s == 3);
    }

    /**
     * @test
     */
    public function hex_length()
    {
        $hex1 = new Hex(-2, 1);

        $this->assertEquals(2, $hex1->length());
    }

    /**
     * @test
     */
    public function hex_distance()
    {
        $hex1 = new Hex(-2, 1);

        $hex2 = new Hex(2, 2);

        $hex3 = new Hex(0, -3);

        $this->assertEquals(5, $hex1->distance($hex2));
        $this->assertEquals(4, $hex1->distance($hex3));
    }
    
    public function hexTwoMinusOneNeighbors()
    {
        return [
            [0, 3, -1], [1, 3, -2], [2, 2, -2],
            [3, 1, -1], [4, 1, 0], [5, 2, 0]
        ];
    }

    /**
     * @test
     *
     * @dataProvider hexTwoMinusOneNeighbors
     */
    public function hex_neighbor($n, $nq, $nr)
    {
        $hex = new Hex(2, -1);
        $neighbor = new Hex($nq, $nr);

        $this->assertTrue($hex->neighbor($n)->equals($neighbor));
    }

    public function hexMinusOneOneDiagonalNeighbor()
    {
        return [
            [0, 1, 0], [1, 0, -1], [2, -2, -0],
            [3, -3, 2], [4, -2, 3], [5, 0, 2]
        ];
    }

    /**
     * @test
     *
     * @dataProvider hexMinusOneOneDiagonalNeighbor
     */
    public function hex_diagonal_neighbor($n, $nq, $nr)
    {
        $hex = new Hex(-1, 1);
        $neighbor = new Hex($nq, $nr);

        $this->assertTrue($hex->diagonalNeighbor($n)->equals($neighbor));
    }

    /**
     * @test
     */
    public function hex_round()
    {
        $a = new Hex(0.0, 0.0);
        $b = new Hex(1.0, -1.0);
        $c = new Hex(0.0, -1.0);

        $this->assertTrue(
            Hex::round(
                $a
            )->equals(
                Hex::round(new Hex(
                    $a->q * 0.4 + $b->q * 0.3 + $c->q * 0.3,
                    $a->r * 0.4 + $b->r * 0.3 + $c->r * 0.3
                ))
            )
        );

        $this->assertTrue(
            Hex::round(
                $c
            )->equals(
                Hex::round(new Hex(
                    $a->q * 0.3 + $b->q * 0.3 + $c->q * 0.4,
                    $a->r * 0.3 + $b->r * 0.3 + $c->r * 0.4
                ))
            )
        );

        $this->assertTrue(
            Hex::round(
                (new Hex(0.0, 0.0))->lerp(new Hex(10.0, -20.0), 0.5)
            )->equals(new Hex(5, -10))
        );

        $this->assertTrue(
            Hex::round(
                $a
            )->equals(
                Hex::round(
                    $a->lerp($b, 0.499)
                )
            )
        );

        $this->assertTrue(
            Hex::round(
                $b
            )->equals(
                Hex::round(
                    $a->lerp($b, 0.501)
                )
            )
        );
    }

    public function lineDrawDataProvider()
    {
        // [0] = start [1] = target [2] = line
        return [
            [
                new Hex(0, 0), new Hex(1, -5),
                [
                    new Hex(0, 0), new Hex(0, -1), new Hex(0, -2),
                    new Hex(1, -3), new Hex(1, -4), new Hex(1, -5)
                ]
            ],
            [
                new Hex(-2, 2), new Hex(2, -1),
                [
                    new Hex(-2, 2), new Hex(-1, 1), new Hex(0, 1),
                    new Hex(1, 0), new Hex(2, -1)
                ]
            ],
            [
                new Hex(-1, 0), new Hex(2, -1),
                [
                    new Hex(-1, 0), new Hex(0, 0),
                    new Hex(1, -1), new Hex(2, -1)
                ]
            ],
        ];
    }
    
    /**
     * @test
     *
     * @dataProvider lineDrawDataProvider
     */
    public function draw_a_line_from_hex_to_hex($start, $target, $line)
    {
        $this->assertEquals(
            $line,
            $start->linedraw($target)
        );
    }
}