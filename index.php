<?php

spl_autoload_extensions('.php');
spl_autoload_register();

require('Game.php');
require_once ('pg.php');

//$state = new State;
//$state->movePiece(0, 0, 4, 4);
//\PGDB\insertState('game-id-4', $state);

$state = PGDB\getState('game-id-4');
print_r($state);

?>