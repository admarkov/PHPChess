<?php

require_once 'State.php';
require_once 'pg/conn.php';

class Game {
    private $id;
    private $playerWHITE;
    private $playerBLACK;
    private $state;

    public function __construct()
    {
        if (func_num_args() == 2) {
            $this->createGame(func_get_arg(0), func_get_arg(1));
        } else if(func_num_args() == 1) {
            $this->loadGame(func_get_arg(0));
        } else {
            throw new Exception("no such constructor for Game");
        }
    }

    private static function generateId() {
        return str_replace('.', '-', uniqid('', true));
    }

    private function createGame($player1, $player2) {
        $this->id = self::generateId();
        $this->playerWHITE = $player1;
        $this->playerBLACK = $player2;
        $pg = new PGConnection;
        $pg->insertGame($this->id, $this->playerWHITE, $this->playerBLACK);
        $pg->insertState($this->id, new State);
    }

    private function loadGame($id) {
        $pg = new PGConnection;
        $this->state = $pg->getState($id);
        $gameData = $pg->getGame($id);
        $this->id = $gameData['id'];
        $this->playerWHITE = $gameData['white'];
        $this->playerBLACK = $gameData['black'];
    }

    public function getColorByUserId($id)
    {
        if ($this->playerWHITE == $id)  {
            return COLOR_WHITE;
        } else if ($this->playerBLACK == $id) {
            return COLOR_BLACK;
        } else {
            throw new Exception('wrong user id');
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getWhitePlayerId() {
        return $this->playerWHITE;
    }

    public function getBlackPlayerId() {
        return $this->playerBLACK;
    }
}