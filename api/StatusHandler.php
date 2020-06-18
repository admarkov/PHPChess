<?php

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/APIHandler.php';

class StatusHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        parent::setMethod(METHOD_GET);

        parent::addRequiredField('gameId');

        parent::setRequestData($_GET);
    }

    protected function handlePreparedRequest()
    {
        $gameId = parent::requestParam('gameId');
        try {
            $game = new Game($gameId);
        } catch (Exception $e) {
            return parent::fail(500, ERRCODE_PG_ERROR, $e->getMessage());
        }
        if (!$game->existing()) {
            return parent::fail(404, 'game_not_found', "no such game: {$gameId}");
        }
        $response = array();
        $response['activePlayer'] = $game->state->getActivePlayerClr();
        $response['pieces'] = array();
        foreach ($game->state->listPieces() as $piece) {
            $response['pieces'][] = $piece->toArray();
        }
        return $this->sendResponse($response);
    }
}
