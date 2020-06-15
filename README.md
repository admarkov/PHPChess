# Backend шахматной партии на PHP

* хранить состояние партии (положение фигур на доске и очерёдность хода),
* проверять ход на соответствие правилам,
* определять конец игры,
* написать API:
* cделать ход,
    * POST /move
    * request:
        * user-token
        * game-id
        * move (E2E4)
        * optional promote (pieceType)
    * response:
        * status (toggleMove, endOfGame, rulesCorruption, serverError, clientError, возможно шах)
* cтатус партии,
    * GET /state
    * request:
        * game-id
    * response:
        * state (поле, кто ходит)
* начать новую партию,
    * POST /start
    * request:
        * playerId1
        * playerId2
    * response:
        * status
        * gameId
* продумать какие типы ошибок могут быть