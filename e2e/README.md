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

## `rungame.py`

Утилита для интеграционного end-to-end тестирования

#### Использование:

##### 1. Канонизация (`canon`)

Возможно два режима канонизации. В интерактивном режиме ходы считываются из стандартного входа. В неинтерактивном они считываются из файла.

* `--host` - адрес сервера. Например, `chess.admarkov.com`
* `-i | --input` - входной файл. В интерактивном режиме туда пишутся ходы, в неинтерактивном из него читаются.
* `-o | --output` - выходной файл. В него записываются ответы сервера.
* `--interactive` - флаг интерактивности режима.

Примеры:

 `python rungame.py canon --host chess.admarkov.com -i tests/game2 -o tests/game2.canondata`
 
 `python rungame.py canon --host chess.admarkov.com -i tests/newgame -o tests/newgame.canondata --interactive`

##### 2. Запуск тестов 

Запускает игру на входных данных, сверяет ответы сервера с канонизированными. Выводится разница, если тест работает и ее нет, не выводится ничего.

* `--host` - адрес сервера. Например, `chess.admarkov.com`
* `-i | --input` - входной файл, содержащий последовательность ходов.
* `-o | --output` - файл с канонизированными ответами.

Пример:

`python rungame.py test --host chess.admarkov.com -i tests/game2 -o tests/game2.canondata`