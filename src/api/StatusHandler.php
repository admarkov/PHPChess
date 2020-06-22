<?php

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/APIHandler.php';

// обработчик ручки /status
class StatusHandler extends APIHandler
{
    public function __construct()
    {
        parent::__construct();

        $this->setMethod(METHOD_GET);

        $this->addRequiredField('game_id');

        $this->setRequestData($_GET);
    }

    // перегруженный обработик подготовленного запроса
    protected function handlePreparedRequest()
    {
        $gameId = $this->requestParam('game_id');
        try {
            $game = new Game($gameId);
        } catch (Exception $e) {
            return $this->fail(500, ERRCODE_PG_ERROR, $e->getMessage());
        }
        if (!$game->existing())
        {
            return $this->fail(404, 'game_not_found', "no such game: {$gameId}");
        }
        $response = array();
        $response['game_status'] = $game->status();
        $response['active_player'] = $game->state->getActivePlayerClr();
        $response['pieces'] = array();
        foreach ($game->state->listPieces() as $piece)
        {
            $response['pieces'][] = $piece->toArray();
        }
        return $this->sendResponse($response);
    }
}
