<?php

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/APIHandler.php';

class StatusHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        $this->setMethod(METHOD_GET);

        $this->addRequiredField('gameId');

        $this->setRequestData($_GET);
    }

    protected function handlePreparedRequest()
    {
        $gameId = $this->requestParam('gameId');
        try {
            $game = new Game($gameId);
        } catch (Exception $e) {
            return $this->fail(500, ERRCODE_PG_ERROR, $e->getMessage());
        }
        if (!$game->existing()) {
            return $this->fail(404, 'game_not_found', "no such game: {$gameId}");
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
