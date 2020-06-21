# Утилиты на питоне

## `cc.py` (от Console Client)

Простой референс-клиент на питоне.

#### Использование:

##### Создание игры

`python cc.py start --host chess.admarkov.com`

##### Получение статуса игры

`python cc.py status --host chess.admarkov.com --game-id GAME_ID`

##### Осуществление хода

`python cc.py move --host chess.admarkov.com --game-id GAME_ID -s E2 -f E4`