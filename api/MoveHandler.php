<?php

require_once __DIR__ . '/APIHandler.php';
require_once __DIR__ . '/../Game.php';

class MoveHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        parent::setMethod(METHOD_POST);

        parent::addRequiredField('gameId');
        parent::addRequiredField('from');
        parent::addRequiredField('to');

        parent::setRequestData($_POST);
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
        $gameId = parent::requestParam('gameId');
        list($y1, $x1) = $this->decodeCoordinate(parent::requestParam('from'));
        list($y2, $x2) = $this->decodeCoordinate(parent::requestParam('to'));
        if (!isset($y1) or !isset($y2) or !isset($x1) or !isset($x2)) {
            return parent::fail(400, ERRCODE_BAD_PARAMS, 'cant decode coordinates');
        }
        $game = new Game($gameId);
        $moveResult = $game->make_move($y1, $x1, $y2, $x2);
        if (!isset($moveResult)) {
            return parent::sendResponse(array());
        } else {
            return parent::fail(400, $moveResult, '');
        }
    }
}