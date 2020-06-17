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

    function updateState($gameId, $state) {
        $request = self::$conn->prepare('UPDATE states SET "state" = ? WHERE "gameId" = ? ;');
        $request->bindValue(1, pg_escape_bytea(serialize($state)));
        $request->bindValue(2, $gameId);
        $request->execute();
    }

    function insertState($gameId, $state) {
        $request = self::$conn->prepare('INSERT INTO states("gameId", "state") VALUES (?, ?);');
        $request->bindValue(1, $gameId);
        $request->bindValue(2, pg_escape_bytea(serialize($state)));
        $request->execute();
    }

}
