<?php

require_once 'pg/cred.php';

class Env {
    public $pg;
}

$env = new Env;

// --- SAMPLE ENVIRONMENT ---

$env->pg = new PGCredentials();
$env->pg->host = "notebookdb.cdq8zesleh3d.us-east-2.rds.amazonaws.com";
$env->pg->port = 5432;
$env->pg->dbname = 'chess';
$env->pg->username = 'chess_user';
$env->pg->password = 'kjkszpj';
