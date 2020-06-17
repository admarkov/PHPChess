<?php

require_once 'pg/cred.php';

class Env {
    public $pg;
}

$env = new Env;

// --- SAMPLE ENVIRONMENT ---

$env->pg = new PGCredentials();
$env->pg->host = "host";
$env->pg->port = 5432;
$env->pg->dbname = 'chess';
$env->pg->username = 'user';
$env->pg->password = 'password';