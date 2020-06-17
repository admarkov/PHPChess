<?php

require_once __DIR__ . '/../Game.php';

function fail($code, $msg) {
    http_response_code($code);
    $response = array();
    $response['error'] = $msg;
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    fail(405, 'Use POST method');
    exit();
}

$player1 = $_POST['player1'];
$player2 = $_POST['player2'];

if (!isset($player1) or !isset($player2)) {
    fail(400, 'required field is missing');
}

try {
    $game = new Game($player1, $player2);
} catch (Exception $e) {
    fail(500, $e->getMessage());
    exit();
}

$response = array();
$response['status'] = "ok";
$response['gameId'] = $game->getId();
$response['white'] = $game->getWhitePlayerId();
$response['black'] = $game->getBlackPlayerId();

http_response_code(200);
echo json_encode($response);
