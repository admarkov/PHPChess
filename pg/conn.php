<?php

require_once __DIR__ . '/../env.php';

class PGConnection {
    private static $conn;

    public function __construct()
    {
        if (!isset(self::$conn)) {
            $this->connect();
        }
    }

    private function connect()
    {
        syslog(LOG_INFO, 'establishing PG connections');
        global $env;
        self::$conn = new PDO($env->pg->dsn(), $env->pg->username, $env->pg->password,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false
            )
        );
    }

    public function getState($gameId) {
        $request = self::$conn->prepare('SELECT * FROM states WHERE "gameId" = ?;');
        $request->bindValue(1, $gameId);
        $request->execute();
        $result = $request->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            ob_start();
            fpassthru($row['state']);
            $dat= ob_get_contents();
            ob_end_clean();
            return unserialize($dat);
        }
        return null;
    }

    public function updateState($gameId, $state) {
        $request = self::$conn->prepare('UPDATE states SET "state" = ? WHERE "gameId" = ? ;');
        $request->bindValue(1, pg_escape_bytea(serialize($state)));
        $request->bindValue(2, $gameId);
        $request->execute();
    }

    public function insertState($gameId, $state) {
        $request = self::$conn->prepare('INSERT INTO states("gameId", "state") VALUES (?, ?);');
        $request->bindValue(1, $gameId);
        $request->bindValue(2, pg_escape_bytea(serialize($state)));
        $request->execute();
    }

    public function getGame($gameId) {
        $request = self::$conn->prepare('SELECT * FROM games WHERE "id" = ?;');
        $request->bindValue(1, $gameId);
        $request->execute();
        $result = $request->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            return $row;
        }
        return null;
    }

    public function insertGame($id, $player1, $player2) {
        $request = self::$conn->prepare('INSERT INTO games("id", "white", "black") VALUES (?, ?, ?);');
        $request->bindValue(1, $id);
        $request->bindValue(2, $player1);
        $request->bindValue(3, $player2);
        $request->execute();
    }
}
