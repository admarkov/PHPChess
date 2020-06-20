<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Game.php';

final class GameTest extends TestCase {

    // ======== TESTS ========

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

    public function testRookMoves()
    {
        $this->check(COLOR_WHITE, 3, 3,
        [
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'WR', 'NN', 'WQ', 'BQ', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN']
        ],
        [
            [0, 0, 0, 1, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0, 0],
            [1, 1, 1, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0]
        ]);
    }

    public function testBishopMoves()
    {
        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'WQ', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WB', 'NN', 'WQ', 'BQ', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'BQ', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 1, 0],
                [0, 0, 0, 0, 0, 1, 0, 0],
                [0, 0, 1, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 1, 0, 1, 0, 0, 0],
                [0, 1, 0, 0, 0, 1, 0, 0],
                [0, 0, 0, 0, 0, 0, 1, 0],
                [0, 0, 0, 0, 0, 0, 0, 1]
            ]);
    }

    public function testQueenMoves()
    {
        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'WQ', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WQ', 'NN', 'WQ', 'BQ', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'BQ', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BQ', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 1, 0, 0, 1, 0],
                [0, 0, 0, 1, 0, 1, 0, 0],
                [0, 0, 1, 1, 1, 0, 0, 0],
                [1, 1, 1, 0, 1, 0, 0, 0],
                [0, 0, 1, 1, 1, 0, 0, 0],
                [0, 1, 0, 1, 0, 1, 0, 0],
                [0, 0, 0, 0, 0, 0, 1, 0],
                [0, 0, 0, 0, 0, 0, 0, 1]
            ]);
    }

    public function testKnightMoves()
    {
        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'WQ', 'NN', 'NN', 'NN', 'BQ', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'Wk', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 1, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 1, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 1, 0, 0, 0, 1, 0, 0],
                [0, 0, 1, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);
    }

    public function testKingMovesDamnTest()
    {
        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WK', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 1, 1, 1, 0, 0, 0],
                [0, 0, 1, 0, 1, 0, 0, 0],
                [0, 0, 1, 1, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);
    }

    public function testPawnMoves()
    {
        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'WP', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_WHITE, 1, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 6, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'WP', 'NN', 'BP', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BP', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 3, 2,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BP', 'BP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WP', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 3, 2,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'WP', 'NN', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WP', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 1, 1, 1, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);
    }

    public function testCheckCheck()
    {
        $game = $this->createGame(COLOR_WHITE,
        [
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'BK', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
            ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
        ]);
        $this->assertTrue($game->isCheck(COLOR_BLACK));
        $this->assertFalse($game->isCheck(COLOR_WHITE));

        $game = $this->createGame(COLOR_WHITE,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BK', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ]);
        $this->assertFalse($game->isCheck(COLOR_WHITE));
        $this->assertFalse($game->isCheck(COLOR_BLACK));

        $game = $this->createGame(COLOR_WHITE,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BK', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ]);
        $this->assertFalse($game->isCheck(COLOR_WHITE));
        $this->assertFalse($game->isCheck(COLOR_BLACK));
    }

    public function testCheckMoves()
    {
        $this->check(COLOR_BLACK, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BK', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 1, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BK', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 1, 0, 0, 0, 0],
                [0, 0, 1, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_BLACK, 2, 2,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WR', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'BK', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'BP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 1, 0, 1, 0, 0, 0, 0],
                [0, 1, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);

        $this->check(COLOR_WHITE, 3, 3,
            [
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'WR', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'WK', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'WP', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'BQ', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN'],
                ['NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN', 'NN']
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 1, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0]
            ]);
    }

    public function testWrongColor()
    {
        $game = new Game(new State);
        $result = $game->make_move(6, 1, 5, 1);
        $this->assertEquals($result, LOGERR_WRONG_COLOR);
    }

    // ======== SUPPLIES ========

    private function resolvePiece($desc, $y, $x)
    {
        if ($desc == 'NN') {
            return null;
        }
        if (strlen($desc) != 2) {
            throw new Exception("wrong piece description: {$desc}");
        }
        if ($desc[0] == 'B') {
            $clr = COLOR_BLACK;
        } else if ($desc[0] == 'W') {
            $clr = COLOR_WHITE;
        } else {
            throw new Exception("wrong piece description: {$desc}");
        }
        if ($desc[1] == 'K') {
            $type = PIECE_KING;
        } else if ($desc[1] == 'Q') {
            $type = PIECE_QUEEN;
        } else if ($desc[1] == 'B') {
            $type = PIECE_BISHOP;
        } else if ($desc[1] == 'k') {
            $type = PIECE_KNIGHT;
        } else if ($desc[1] == 'R') {
            $type = PIECE_ROOK;
        } else if ($desc[1] == 'P') {
            $type = PIECE_PAWN;
        } else {
            throw new Exception("wrong piece description: {$desc}");
        }
        return new Piece($clr, $type, $y, $x);
    }

    private function createGame($activeClr, $board)
    {
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $board[$i][$j] = $this->resolvePiece($board[$i][$j], $i, $j);
            }
        }
        return new Game(new State($activeClr, $board));
    }

    private function checkMove(Game $game, $y1, $x1, $y2, $x2)
    {
        $move = $game->state->getActivePlayerClr();
        $piece1 = clone $game->state->getPiece($y1, $x1);
        $piece2 = $game->state->getPiece($y2, $x2);
        if (isset($piece2)) {
            $piece2 = clone $piece2;
        }
        $result = $game->make_move($y1, $x1, $y2, $x2);
        $piece1After = $game->state->getPiece($y1, $x1);
        $piece2After = $game->state->getPiece($y2, $x2);
        if (isset($result)) {
            $this->assertEquals($piece1, $piece1After);
            $this->assertEquals($piece2, $piece2After);
            $this->assertEquals($move, $game->state->getActivePlayerClr());
            return 0;
        } else {
            $this->assertEquals($piece2After->type, $piece1->type);
            $this->assertEquals($piece2After->color, $piece1->color);
            $this->assertNull($piece1After);
            $this->assertNotNull($piece2After);
            $this->assertEquals($piece2After->x, $x2);
            $this->assertEquals($piece2After->y, $y2);
            $this->assertNotEquals($move, $game->state->getActivePlayerClr());
            return 1;
        }
    }

    private function outputMovesMap($board, $result)
    {
        echo PHP_EOL;
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                echo $board[$i][$j], ' ';
            }
            echo ' | ';
            for ($j = 0; $j < 8; $j++) {
                echo $result[$i][$j], ' ';
            }
            echo PHP_EOL;
        }
    }

    private function checkAllMoves($clr, $board, $y, $x)
    {
        $result = array();
        for ($i = 0; $i < 8; $i++) {
            $row = array();
             for ($j = 0; $j < 8; $j++) {
                 $row[] = $this->checkMove($this->createGame($clr, $board), $y, $x, $i, $j);
             }
             $result[] = $row;
        }
        return $result;
    }

    private function check($clr, $y, $x, $board, $answer)
    {
        $result = $this->checkAllMoves($clr, $board, $y, $x);
        $this->outputMovesMap($board, $result);
        $this->assertEquals($result, $answer);
    }
}