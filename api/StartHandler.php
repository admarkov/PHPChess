<?php

require_once __DIR__ . '/../Game.php';
require_once __DIR__ . '/APIHandler.php';

class StartHandler extends APIHandler {
    public function __construct()
    {
        parent::__construct();

        $this->setMethod(METHOD_POST);
    }

    protected function handlePreparedRequest()
    {
        try {
            $game = new Game;
        } catch (Exception $e) {
            return $this->fail(500, ERRCODE_PG_ERROR, $e->getMessage());
        }
        $response = [
            'gameId' => $game->getId()
        ];
        return $this->sendResponse($response);
    }
}