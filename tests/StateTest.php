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

    public function testCancelMove()
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
}