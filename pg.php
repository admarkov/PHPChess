<?php

namespace PGDB;

function createConnection()
{
    // credentials here...
    return new \PDO(    $dsn, $username, $passwd,
        array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => false
        )
    );
}

$pgconn = createConnection();

function getState($gameId) {
    global $pgconn;
    $request = $pgconn->prepare('SELECT * FROM states WHERE "gameId" = ?;');
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
    global $pgconn;
    $request = $pgconn->prepare('UPDATE states SET "state" = ? WHERE "gameId" = ? ;');
    $request->bindValue(1, pg_escape_bytea(serialize($state)));
    $request->bindValue(2, $gameId);
    $request->execute();
}

function insertState($gameId, $state) {
    global $pgconn;
    $request = $pgconn->prepare('INSERT INTO states("gameId", "state") VALUES (?, ?);');
    $request->bindValue(1, $gameId);
    $request->bindValue(2, pg_escape_bytea(serialize($state)));
    $request->execute();
}

?>