<?php

require_once 'State.php';
require_once 'pg/conn.php';

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
}