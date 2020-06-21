<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../State.php';

final class StateTest extends TestCase
{
    public function testMove()
    {
        $state = new State;

        $state->movePiece(1, 1, 2 ,1);
        $this->assertNotNull($state->getPiece(2, 1));
        $this->assertEquals(2, $state->getPiece(2, 1)->y);
        $this->assertEquals(1, $state->getPiece(2, 1)->x);
        $this->assertNull($state->getPiece(1, 1));
    }

    public function testCancelMoveToEmptyCell()
    {
        $state = new State;

        $state->movePiece(1,  1, 2, 1);

        $this->assertNull($state->getPiece(1, 1));
        $this->assertNotNull($state->getPiece(2, 1));
        $this->assertEquals(2, $state->getPiece(2, 1)->y);

        $state->cancelLastMove();

        $this->assertNull($state->getPiece(2, 1));
        $this->assertNotNull($state->getPiece(1, 1));
        $this->assertEquals(1, $state->getPiece(1, 1)->y);
    }

    public function testCancelMoveToNonEmptyCell()
    {
        $state = new State;
        $state->movePiece(0, 0, 1, 0);
        $this->assertNull($state->getPiece(0, 0));
        $this->assertEquals(PIECE_ROOK, $state->getPiece(1, 0)->type);
        $state->cancelLastMove();
        $this->assertEquals(0, $state->getPiece(0, 0)->y);
        $this->assertEquals(PIECE_ROOK, $state->getPiece(0, 0)->type);
        $this->assertEquals(1, $state->getPiece(1, 0)->y);
        $this->assertEquals(PIECE_PAWN, $state->getPiece(1, 0)->type);
    }
}