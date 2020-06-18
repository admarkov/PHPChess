<?php

require_once 'State.php';
require_once 'pg/conn.php';

// --- Logical errors enumeration ---
const LOGERR_EMPTY_CELL = 'empty_cell';
const LOGERR_WRONG_COLOR = 'wrong_color';
const LOGERR_CELL_NOT_EMPTY = 'cell_not_empty';
const LOGERR_FORBIDDEN_MOVE = 'forbidden_move';

class Game {
    private $id;
    public $state;

    public function __construct()
    {
        if (func_num_args() == 0) {
            $this->createGame();
        } else if(func_num_args() == 1) {
            $this->loadGame(func_get_arg(0));
        } else {
            throw new Exception("no such constructor for Game");
        }
    }

    private static function generateId() {
        return str_replace('.', '-', uniqid('', true));
    }

    private function createGame() {
        $this->id = self::generateId();
        $pg = new PGConnection;
        $pg->insertState($this->id, new State);
    }

    private function loadGame($id) {
        $this->id = $id;
        $pg = new PGConnection;
        $this->state = $pg->getState($id);
    }

    public function getId() {
        return $this->id;
    }

    public function existing() {
        return $this->state != null;
    }

    private function pieceMovable($piece)
    {
        if (!isset($piece)) {
            return LOGERR_EMPTY_CELL;
        }
        if ($piece->color != $this->state->getActivePlayerClr()) {
            return LOGERR_WRONG_COLOR;
        }
        return null;
    }

    private function checkRookMove($piece, $y, $x) {
        $minX = min($piece->x, $x);
        $maxX = max($piece->x, $x);
        $minY = min($piece->y, $y);
        $maxY = max($piece->y, $y);
        if ($piece->x == $x) {
            for ($i = $minY + 1; $i < $maxY; $i++) {
                if ($this->state->getPiece($i, $x) != null) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
            return null;
        }
        if ($piece->y == $y) {
            for ($i = $minX + 1; $i < $maxX; $i++) {
                if ($this->state->getPiece($y, $i) != null) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
            return null;
        }
        return LOGERR_FORBIDDEN_MOVE;
    }

    private function checkKnightMove($piece, $y, $x) {
        if (abs(($piece->x - $x) * ($piece->y - $y)) != 2) {
            return LOGERR_FORBIDDEN_MOVE;
        }
        return null;
    }

    private function checkBishopMove($piece, $y, $x) {
        $minX = min($piece->x, $x);
        $maxX = max($piece->x, $x);
        $minY = min($piece->y, $y);
        $maxY = max($piece->y, $y);
        if (abs($piece->x - $x) != abs($piece->y - $y)) {
            return LOGERR_FORBIDDEN_MOVE;
        }
        if (($piece->x - $x) * ($piece->y - $y) > 0) {
            for ($i = 1; $i < $maxX - $minX; $i++) {
                if ($this->state->getPiece($minY + $i, $minX + $i) != null) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
        } else {
            for ($i = 1; $i < $maxX - $minX; $i++) {
                if ($this->state->getPiece($maxX - $i, $minX + $i) != null) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
        }
        return null;
    }

    private function checkKingMove($piece, $y, $x) {
        if (abs($piece->x - $x) > 1 or abs($piece->y - $y) > 1) {
            return LOGERR_FORBIDDEN_MOVE;
        }
        return null;
    }

    private function checkQueenMove($piece, $y, $x) {
        $checkBishop = $this->checkBishopMove($piece, $y, $x);
        $checkRook = $this->checkRookMove($piece, $y, $x);
        if ($checkBishop == null or $checkRook == null) {
            return null;
        }
        return LOGERR_FORBIDDEN_MOVE;
    }

    private function checkPawnMove($piece, $y, $x) {
        $piece2 = $this->state->getPiece($y, $x);
        if (abs(($piece->x - $x)*($piece->y - $y)) == 1) {
            if (!isset($piece2)) {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece2->color == $piece->color) {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_BLACK and $piece->y > $y) {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_WHITE and $piece->y < $y) {
                return LOGERR_FORBIDDEN_MOVE;
            }
        }
        else {
            if ($piece->x != $x) {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if (isset($piece2)) {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_BLACK) {
                if (!($y - $piece->y == -1 or $y - $piece->y == -2 and $piece->y == 6 and $this->state->getPiece(5, $x) == null)) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
            else {
                if (!($y - $piece->y == 1 or $y - $piece->y == 2 and $piece->y == 1 and $this->state->getPiece(2, $x) == null)) {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
        }
        return null;
    }

    private function moveAccepted($piece, $y, $x)
    {
        $piece2 = $this->state->getPiece($y, $x);
        if (isset($piece2) and $piece->color == $piece2->color) {
            return LOGERR_CELL_NOT_EMPTY;
        }
        if ($piece->y == $y and $piece->x == $x) {
            return LOGERR_FORBIDDEN_MOVE;
        }

        $capitalizeFirst = function($str) {
            $str[0] = strtoupper($str[0]);
            return $str;
        };

        $checkMethod = 'check' . $capitalizeFirst($piece->type) . 'Move';
        $checkResult = $this->$checkMethod($piece, $y, $x);
        if ($checkResult != null) {
            return $checkResult;
        }

        return null;
    }

    public function make_move($y1, $x1, $y2, $x2) {
        if (!State::checkCoordinates($y1, $x1) or !State::checkCoordinates($y2, $x2)) {
            return ERRCODE_BAD_PARAMS;
        }

        $piece = $this->state->getPiece($y1, $x1);

        $movable = $this->pieceMovable($piece);
        if (isset($movable)) {
            return $movable;
        }

        $accepted = $this->moveAccepted($piece, $y2, $x2);
        if (isset($accepted)) {
            return $accepted;
        }

        $this->state->movePiece($y1, $x1, $y2, $x2);
        $this->state->toggleActivePlayer();

        $pg = new PGConnection;
        $pg->updateState($this->id, $this->state);

        return null;
    }
}