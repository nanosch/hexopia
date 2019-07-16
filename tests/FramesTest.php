<?php

namespace Test;

use Hexopia\Map\ConsolePlotter\FrameFactory;
use Hexopia\Map\ConsolePlotter\Frames\HexFrame;
use Hexopia\Map\Shapes\HexMap;
use Hexopia\Map\Shapes\TriangleMap;

class FramesTest extends \PHPUnit\Framework\TestCase
{
    public function hexFrameAssertions()
    {
        // Radius, Anz-Hex
        return [
            [0, 1, 5, 9], [1, 7, 13, 23],
            [2, 19, 21, 37], [3, 37, 29, 51]
        ];
    }

    /**
     * @test
     */
    public function get_correct_frame_hex()
    {
        $map = HexMap::hex(1);

        $frame = FrameFactory::make($map);

        $this->assertInstanceOf(HexFrame::class, $frame);
    }

    /**
     * @test
     *
     * @dataProvider hexFrameAssertions
     */
    public function hex_frame_data($rings, $hexagons, $rows, $charsPerRow)
    {
        $frame = FrameFactory::make(
            HexMap::hex($rings)
        );

        $this->assertCount($rows, $frame->display);

        for ($i = 0; $i < $rows; $i++){
            $this->assertCount($charsPerRow, $frame->display[0]);
        }
    }
    
    /**
     * @test
     */
    public function place_empty_hex_in_hex_frame()
    {
        $frame = FrameFactory::make(
            HexMap::hex(1)
        );

        $frame->render();

        $this->assertEquals('.', $frame->display[2][11]);
        $this->assertEquals('.', $frame->display[4][4]);
        $this->assertEquals('.', $frame->display[4][18]);
        $this->assertEquals('.', $frame->display[8][4]);
        $this->assertEquals('.', $frame->display[8][18]);
        $this->assertEquals('.', $frame->display[10][11]);
        $this->assertEquals('.', $frame->display[6][11]);
    }

    /**
     * @test
     */
    public function place_empty_hex_in_triangle_frame()
    {
        $frame = FrameFactory::make(
            TriangleMap::triangle(2)
        );

        $frame->render();

        $this->assertEquals('.', $frame->display[2][4]);
        $this->assertEquals('.', $frame->display[6][4]);
        $this->assertEquals('.', $frame->display[10][4]);
        $this->assertEquals('.', $frame->display[4][11]);
        $this->assertEquals('.', $frame->display[8][11]);
        $this->assertEquals('.', $frame->display[6][18]);
    }
}