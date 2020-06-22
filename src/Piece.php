<?php
// EColor
const COLOR_WHITE = "white";
const COLOR_BLACK = "black";

// EType
const PIECE_KING = "king";
const PIECE_QUEEN = "queen";
const PIECE_BISHOP = "bishop";
const PIECE_KNIGHT = "knight";
const PIECE_ROOK = "rook";
const PIECE_PAWN = "pawn";

// Класс, описывающий шахматную фигуру
class Piece
{
    public $color;
    public $type;
    public $y;
    public $x;

    public function __construct($_clr, $_type, $y, $x)
    {
        $this->color = $_clr;
        $this->type = $_type;
        $this->y = $y;
        $this->x = $x;
    }

    // Переводит координату в двумерной 0-индексации в шахматную нотацию (E2)
    public function chessNotationCoordinate()
    {
        return chr(ord('A') + $this->x) . chr(ord('1') + $this->y);
    }

    // Осуществляет превращение фигуры в королеву (promoting по простым правилам без выбора)
    public function promote()
    {
        $this->type = PIECE_QUEEN;
    }

    // Конвертирует объект Piece в ассоциативный массив
    public function toArray()
    {
        return [
            'color' => $this->color,
            'type' => $this->type,
            'coordinate' => $this->chessNotationCoordinate()
        ];
    }
}