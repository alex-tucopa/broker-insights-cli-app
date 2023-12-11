<?php

use Symfony\Component\Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

$envFilename = __DIR__ . '/../.env';

if (is_readable($envFilename)) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/../.env');
}

$dbConfig = require __DIR__ . '/../config/database.php';

$dbConnectionType = $dbConfig['connection_type'];
$dbConnectionParams = $dbConfig['connections'][$dbConnectionType];

if (!$dbConnectionParams) {
    trigger_error('Database connection not set', E_USER_ERROR );
}

$capsule = new Capsule();

$capsule->addConnection($dbConnectionParams);

$capsule->setAsGlobal();
$capsule->bootEloquent();
