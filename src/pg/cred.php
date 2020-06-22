<?php

class PGCredentials {
    public $host;
    public $port;
    public $dbname;
    public $username;
    public $password;

    public function dsn()
    {
        return "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname";
    }

}