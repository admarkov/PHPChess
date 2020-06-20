<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/../PG/conn.php';

final class GameTest extends TestCase {
    public function testGameConstructors() {
        $game = new Game;
        $this->assertTrue($game->existing());
        $game2 = new Game($game->getId());
        $this->assertTrue($game2->existing());
        $state = new State(COLOR_BLACK, array(1, 2, 3));
        $this->assertEquals($state->getActivePlayerClr(), COLOR_BLACK);
        $this->assertEquals($state->getBoard(), array(1, 2, 3));
        $game3 = new Game($state);
        $this->assertTrue($game3->existing());
        $game4 = new Game($game3->getId());
        $this->assertFalse($game4->existing());
    }
}