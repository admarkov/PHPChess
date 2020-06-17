<?php

spl_autoload_extensions('.php');
spl_autoload_register();

require_once('pg/conn.php');

$pg = new PGConnection;

$state = new State;
$state->movePiece(0, 0, 4, 4);
$pg->insertState('game-id-5', $state);

$state = $pg->getState('game-id-5');
print_r($state);

?>