<?php

require_once __DIR__ . '/APIHandler.php';
require_once __DIR__ . '/../Game.php';

class MoveHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        $this->setMethod(METHOD_POST);

        $this->addRequiredField('game_id');
        $this->addRequiredField('from');
        $this->addRequiredField('to');

        $this->setRequestData($_POST);
    }

    private function decodeCoordinate($coordinate)
    {
        if (strlen($coordinate) != 2) {
            return array(null, null);
        }
        $y = ord($coordinate[1]) - ord('1');
        $x = ord($coordinate[0]) - ord('A');
        return array($y, $x);
    }

    protected function handlePreparedRequest()
    {
        $gameId = $this->requestParam('game_id');
        list($y1, $x1) = $this->decodeCoordinate($this->requestParam('from'));
        list($y2, $x2) = $this->decodeCoordinate($this->requestParam('to'));
        if (!isset($y1) or !isset($y2) or !isset($x1) or !isset($x2)) {
            return $this->fail(400, ERRCODE_BAD_PARAMS, 'cant decode coordinates');
        }
        $game = new Game($gameId);
        if (!$game->existing())
        {
            return $this->fail(404, 'no such game', '');
        }
        $moveResult = $game->make_move($y1, $x1, $y2, $x2);
        if (!isset($moveResult)) {
            return $this->sendResponse(array());
        } else {
            return $this->fail(400, $moveResult, '');
        }
    }
}