<?php
// EColor
const COLOR_WHITE = "white";
const COLOR_BLACK = "black";

// EType
const PIECE_KING = "king";      // король
const PIECE_QUEEN = "queen";    // ферзь
const PIECE_BISHOP = "bishop";  // слон
const PIECE_KNIGHT = "knight";  // конь
const PIECE_ROOK = "rook";      // ладья
const PIECE_PAWN = "pawn";      // пешка

class Piece {
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
}