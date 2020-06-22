<?php

require_once 'State.php';
require_once 'pg/conn.php';

// --- Logical errors enumeration ---
const LOGERR_EMPTY_CELL = 'empty_cell';
const LOGERR_WRONG_COLOR = 'wrong_color';
const LOGERR_CELL_NOT_EMPTY = 'cell_not_empty';
const LOGERR_FORBIDDEN_MOVE = 'forbidden_move';
const LOGERR_CHECK = 'check';
const LOGERR_GAME_FINISHED = 'game_finished';

// --- Game statuses enumeration ---
const GAME_STATUS_REGULAR = 'regular';
const GAME_STATUS_CHECK_WHITE = 'check_white';
const GAME_STATUS_CHECK_BLACK = 'check_black';
const GAME_STATUS_CHECKMATE_WHITE = 'checkmate_white';
const GAME_STATUS_CHECKMATE_BLACK = 'checkmate_black';

class Game
{
    private $id;
    private $synchronize = true;

    public ?State $state;

    // Proxy-конструктор.
    // Game() - создает новую игру
    // Game(str) - загружает игру по id
    // Game(state) - создает игру с данным состоянием. Такие игры нужны для тестов и не синхронизируются с postgres.
    public function __construct()
    {
        if (func_num_args() == 0)
        {
            $this->createGame();
            return;
        } else if(func_num_args() == 1)
        {
            $arg = func_get_arg(0);
            if (is_string($arg))
            {
                $this->loadGame($arg);
                return;
            } else if ($arg instanceof State)
            {
                // test usage only
                $this->synchronize = false;
                $this->createGame($arg);
                return;
            }
        }
        throw new Exception('no such constructor for game');
    }

    // Функция для генерации идентификатора игры
    private static function generateId()
    {
        return str_replace('.', '-', uniqid('', true));
    }

    // Создает игру, реализуя конструкторы Game() и Game(state)
    private function createGame($state = null)
    {
        $this->id = self::generateId();
        if (!isset($state))
        {
            $state = new State;
        }
        $this->state = $state;
        if ($this->synchronize)
        {
            $pg = new PGConnection;
            $pg->insertState($this->id, $this->state);
        }
    }

    // Загружает игру по id, реализуя конструктор Game(str)
    private function loadGame($id)
    {
        $this->id = $id;
        $pg = new PGConnection;
        $this->state = $pg->getState($id);
    }

    // Возвращает id игры
    public function getId()
    {
        return $this->id;
    }

    // Возвращает, инициализирована ли игра
    public function existing()
    {
        return $this->state != null;
    }

    // Проверяет, можно ли осуществить перемещение фигуры в принципе
    // Возвращает код ошибки или null, если фигура может ходить
    private function pieceMovable($piece)
    {
        if (!isset($piece))
        {
            return LOGERR_EMPTY_CELL;
        }
        if ($piece->color != $this->state->getActivePlayerClr())
        {
            return LOGERR_WRONG_COLOR;
        }
        return null;
    }

    // Проверяет на корректность ход ладьи
    private function checkRookMove($piece, $y, $x)
    {
        $minX = min($piece->x, $x);
        $maxX = max($piece->x, $x);
        $minY = min($piece->y, $y);
        $maxY = max($piece->y, $y);
        if ($piece->x == $x)
        {
            for ($i = $minY + 1; $i < $maxY; $i++)
            {
                if ($this->state->getPiece($i, $x) != null)
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
            return null;
        }
        if ($piece->y == $y)
        {
            for ($i = $minX + 1; $i < $maxX; $i++)
            {
                if ($this->state->getPiece($y, $i) != null)
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
            return null;
        }
        return LOGERR_FORBIDDEN_MOVE;
    }

    // Проверяет на корректность ход коня
    private function checkKnightMove($piece, $y, $x)
    {
        if (abs(($piece->x - $x) * ($piece->y - $y)) != 2)
        {
            return LOGERR_FORBIDDEN_MOVE;
        }
        return null;
    }

    // Проверяет на корректность ход слона
    private function checkBishopMove($piece, $y, $x)
    {
        $minX = min($piece->x, $x);
        $maxX = max($piece->x, $x);
        $minY = min($piece->y, $y);
        $maxY = max($piece->y, $y);
        if (abs($piece->x - $x) != abs($piece->y - $y))
        {
            return LOGERR_FORBIDDEN_MOVE;
        }
        if (($piece->x - $x) * ($piece->y - $y) > 0)
        {
            for ($i = 1; $i < $maxX - $minX; $i++)
            {
                if ($this->state->getPiece($minY + $i, $minX + $i) != null)
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
        } else {
            for ($i = 1; $i < $maxX - $minX; $i++)
            {
                if ($this->state->getPiece($maxY - $i, $minX + $i) != null)
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
         
            }
        }
        return null;
    }

    // Проверяет на корректность ход короля
    private function checkKingMove($piece, $y, $x)
    {
        if (abs($piece->x - $x) > 1 or abs($piece->y - $y) > 1)
        {
            return LOGERR_FORBIDDEN_MOVE;
        }
        return null;
    }

    // Проверяет на корректность ход ферзя
    private function checkQueenMove($piece, $y, $x)
    {
        $checkBishop = $this->checkBishopMove($piece, $y, $x);
        $checkRook = $this->checkRookMove($piece, $y, $x);
        if ($checkBishop == null or $checkRook == null)
        {
            return null;
        }
        return LOGERR_FORBIDDEN_MOVE;
    }

    // Проверяет на корректность ход пешки
    private function checkPawnMove($piece, $y, $x)
    {
        $piece2 = $this->state->getPiece($y, $x);
        if (abs(($piece->x - $x)*($piece->y - $y)) == 1)
        {
            if (!isset($piece2))
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece2->color == $piece->color)
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_BLACK and $piece->y < $y)
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_WHITE and $piece->y > $y)
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
        } else {
            if ($piece->x != $x)
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if (isset($piece2))
            {
                return LOGERR_FORBIDDEN_MOVE;
            }
            if ($piece->color == COLOR_BLACK)
            {
                if (!($y - $piece->y == -1 or $y - $piece->y == -2 and $piece->y == 6 and $this->state->getPiece(5, $x) == null))
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            } else {
                if (!($y - $piece->y == 1 or $y - $piece->y == 2 and $piece->y == 1 and $this->state->getPiece(2, $x) == null))
                {
                    return LOGERR_FORBIDDEN_MOVE;
                }
            }
        }
        return null;
    }

    // Проверяет, можно ли совершить ход фигуры $piece в клетку ($y, $x)
    // Возвращает код ошибки или null, если ход возможен
    private function moveAccepted($piece, $y, $x)
    {
        if ($piece->y == $y and $piece->x == $x)
        {
            return LOGERR_FORBIDDEN_MOVE;
        }

        $piece2 = $this->state->getPiece($y, $x);
        if (isset($piece2) and $piece->color == $piece2->color)
        {
            return LOGERR_CELL_NOT_EMPTY;
        }
        $capitalizeFirst = function($str) {
            $str[0] = strtoupper($str[0]);
            return $str;
        };

        $checkMethod = 'check' . $capitalizeFirst($piece->type) . 'Move';
        $checkResult = $this->$checkMethod($piece, $y, $x);
        if ($checkResult != null)
        {
            return $checkResult;
        }

        return null;
    }

    // Возвращает фигуру на доске короля указанного цвета, если такая есть
    private function findKing($clr)
    {
        for ($i = 0; $i < 8; $i++)
        {
            for ($j = 0; $j < 8; $j++)
            {
                $piece = $this->state->getPiece($i, $j);
                if (isset($piece) and $piece->type == PIECE_KING and $piece->color == $clr)
                {
                    return $piece;
                }
            }
        }
        return null;
    }

    // Проверяет, поставлен ли ходящему игроку шах
    public function isCheck()
    {
        $king = $this->findKing($this->state->getActivePlayerClr());
        if (!isset($king))
        {
            return false;
        }
        for ($i = 0; $i < 8; $i++)
        {
            for ($j = 0; $j < 8; $j++)
            {
                $piece = $this->state->getPiece($i, $j);
                if (isset($piece) and $piece->color != $this->state->getActivePlayerClr())
                {
                    if (is_null($this->moveAccepted($piece, $king->y, $king->x)))
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    // Проверяет, ведет ли ход фигуры $piece в клетку с y-координатой $y к превращению пешки
    private function isPromotion($piece, $y)
    {
        if (!isset($piece))
        {
            return false;
        }
        if ($piece->type == PIECE_PAWN)
        {
            if ($piece->color == COLOR_WHITE and $piece->y == 6 and $y == 7)
            {
                return true;
            }
            if ($piece->color == COLOR_BLACK and $piece->y == 1 and $y == 0)
            {
                return true;
            }
            return false;
        }
        return false;
    }

    // Осуществляет ход из ($y1, $x1) в ($y2, $x2), совершая все необходимые проверки.
    // Возвращает код ошибки или null, если ход корректен
    public function make_move($y1, $x1, $y2, $x2)
    {
        if ($this->isCheckmate())
        {
            return LOGERR_GAME_FINISHED;
        }

        if (!State::checkCoordinates($y1, $x1) or !State::checkCoordinates($y2, $x2))
        {
            return ERRCODE_BAD_PARAMS;
        }

        $piece = $this->state->getPiece($y1, $x1);

        $movable = $this->pieceMovable($piece);
        if (isset($movable))
        {
            return $movable;
        }

        $accepted = $this->moveAccepted($piece, $y2, $x2);
        if (isset($accepted))
        {
            return $accepted;
        }

        $promotion = $this->isPromotion($piece, $y2);
        $this->state->movePiece($y1, $x1, $y2, $x2);
        if ($this->isCheck())
        {
            $this->state->cancelLastMove();
            return LOGERR_CHECK;

        }
        $this->state->toggleActivePlayer();
        if ($promotion)
        {
            $piece->promote();
        }

        if ($this->synchronize)
        {
            $pg = new PGConnection;
            $pg->updateState($this->id, $this->state);
        }

        return null;
    }

    // Проверяет, поставлен ли ходящему игроку мат
    public function isCheckmate()
    {
        for ($y1 = 0; $y1 < 8; $y1++)
        {
            for ($x1 = 0; $x1 < 8; $x1++)
            {
                $piece = $this->state->getPiece($y1, $x1);
                if (isset($piece) and $piece->color == $this->state->getActivePlayerClr())
                {
                    for ($y2 = 0; $y2 < 8; $y2++)
                    {
                        for ($x2 = 0; $x2 < 8; $x2++)
                        {
                            $piece = $this->state->getPiece($y1, $x1);
                            if (is_null($this->moveAccepted($piece, $y2, $x2))) {
                                $this->state->movePiece($y1, $x1, $y2, $x2);
                                $safeMove = !$this->isCheck();
                                $this->state->cancelLastMove();
                                if ($safeMove == true)
                                {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    // Возвращает статус игры. Один из:
    // regular - обычное состояние
    // check_COLOR - шах игроку COLOR
    // checkmate_COLOR - мат игроку COLOR
    public function status()
    {
        if ($this->isCheckmate())
        {
            if ($this->state->getActivePlayerClr() == COLOR_WHITE)
            {
                return GAME_STATUS_CHECKMATE_WHITE;
            } else {
                return GAME_STATUS_CHECKMATE_BLACK;
            }
        }
        if ($this->isCheck())
        {
            if ($this->state->getActivePlayerClr() == COLOR_WHITE)
            {
                return GAME_STATUS_CHECK_WHITE;
            } else {
                return GAME_STATUS_CHECK_BLACK;
            }
        }
        return GAME_STATUS_REGULAR;
    }
}