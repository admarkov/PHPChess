<?php

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/APIHandler.php';

class StartHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        parent::setMethod(METHOD_POST);

        parent::setRequestData($_POST);

        parent::addRequiredField('player1');
        parent::addRequiredField('player2');
    }

    protected function handlePreparedRequest()
    {
        $player1 = parent::requestParam('player1');
        $player2 = parent::requestParam('player2');
        try {
            $game = new Game($player1, $player2);
        } catch (Exception $e) {
            return parent::fail(500, ERRCODE_PG_ERROR, $e->getMessage());
        }
        $response = [
            'gameId' => $game->getId(),
            'white' => $game->getWhitePlayerId(),
            'black' => $game->getBlackPlayerId()
        ];
        return parent::sendResponse($response);
    }
}