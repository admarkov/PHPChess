<?php

require_once 'Piece.php';

class State {
    private $activePlayerClr = COLOR_WHITE;
    private $board = array();

    // Проверяет координату на принадлежность доске
    static public function checkCoordinate($z)
    {
        return $z >= 0 && $z <= 7;
    }

    // Проверяет пару координат на принадлежность доске
    static public function checkCoordinates($x, $y)
    {
        return self::checkCoordinate($x) && self::checkCoordinate($y);
    }

    public function __construct($initialActivePlayer = COLOR_WHITE, $initialBoard = null)
    {
        if (isset($initialBoard)) {
            $this->board = $initialBoard;
        } else {
            for ($i = 0; $i < 8; $i++) {
                $this->board[] = [null, null, null, null, null, null, null, null];
            }
            $this->board[0][0] = new Piece(COLOR_WHITE, PIECE_ROOK, 0, 0);
            $this->board[0][1] = new Piece(COLOR_WHITE, PIECE_KNIGHT, 0, 1);
            $this->board[0][2] = new Piece(COLOR_WHITE, PIECE_BISHOP, 0, 2);
            $this->board[0][3] = new Piece(COLOR_WHITE, PIECE_QUEEN, 0, 3);
            $this->board[0][4] = new Piece(COLOR_WHITE, PIECE_KING, 0, 4);
            $this->board[0][5] = new Piece(COLOR_WHITE, PIECE_BISHOP, 0, 5);
            $this->board[0][6] = new Piece(COLOR_WHITE, PIECE_KNIGHT, 0, 6);
            $this->board[0][7] = new Piece(COLOR_WHITE, PIECE_ROOK, 0, 7);

            $this->board[7][0] = new Piece(COLOR_BLACK, PIECE_ROOK, 7, 0);
            $this->board[7][1] = new Piece(COLOR_BLACK, PIECE_KNIGHT, 7, 1);
            $this->board[7][2] = new Piece(COLOR_BLACK, PIECE_BISHOP, 7, 2);
            $this->board[7][3] = new Piece(COLOR_BLACK, PIECE_QUEEN, 7, 3);
            $this->board[7][4] = new Piece(COLOR_BLACK, PIECE_KING, 7, 4);
            $this->board[7][5] = new Piece(COLOR_BLACK, PIECE_BISHOP, 7, 5);
            $this->board[7][6] = new Piece(COLOR_BLACK, PIECE_KNIGHT, 7, 6);
            $this->board[7][7] = new Piece(COLOR_BLACK, PIECE_ROOK, 7, 7);

            for ($i = 0; $i < 8; $i++) {
                $this->board[1][$i] = new Piece(COLOR_WHITE, PIECE_PAWN, 1, $i);
                $this->board[6][$i] = new Piece(COLOR_BLACK, PIECE_PAWN, 6, $i);
            }
        }

        $this->activePlayerClr = $initialActivePlayer;
    }

    public function __clone()
    {
        $board = array();
        for ($i = 0; $i < 8; $i++) {
            $row = array();
            for ($j = 0; $j < 8; $j++) {
                if (isset($board[$i][$j])) {
                    $row[] = $board[$i][$j];
                }
            }
            $board[] = $row;
        }
    }

    // Возвращает фигуру в клетке, заданной
    // координатами в 0-индексации
    public function getPiece($y, $x): ?Piece
    {
        if (self::checkCoordinates($x, $y)) {
            return $this->board[$y][$x];
        }
        return null;
    }

    // Перемещает фигуру из клетки (y1, x1) в клетку (y2, x2)
    public function movePiece($y1, $x1, $y2, $x2)
    {
        if (!self::checkCoordinates($x1, $y1) || !self::checkCoordinates($x2, $y2)) {
            return false;
        }
        if (!isset($this->board[$y1][$x1])) {
            return false;
        }
        $this->board[$y2][$x2] = $this->board[$y1][$x1];
        $this->board[$y1][$x1] = null;
        $this->board[$y2][$x2]->y = $y2;
        $this->board[$y2][$x2]->x = $x2;
        return true;
    }

    // Возвращает цвет фигур активного игрока
    public function getActivePlayerClr()
    {
        return $this->activePlayerClr;
    }

    // Передает ход другому игроку
    public function toggleActivePlayer()
    {
        if ($this->activePlayerClr == COLOR_WHITE) {
            $this->activePlayerClr = COLOR_BLACK;
        } else {
            $this->activePlayerClr = COLOR_WHITE;
        }
    }

    // Возвращает список фигур на доске
    public function listPieces(): array
    {
        $res = array();
        foreach($this->board as $row)
        {
            foreach($row as $cell)
            {
                if (isset($cell))
                {
                    $res[] = $cell;
                }
            }
        }
        return $res;
    }

    // INTENDED FOR USAGE IN TESTS ONLY
    public function getBoard() {
        return $this->board;
    }
}