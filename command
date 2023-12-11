#!/usr/bin/env php

<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__ . '/bootstrap/application.php';

use Symfony\Component\Console\Application;
use App\Commands\ImportBrokerPolicyDataCommand;
use App\Commands\PolicyReportCommand;
use App\Commands\InitialiseDatabaseCommand;

$application = new Application();

$application->add(new ImportBrokerPolicyDataCommand());
$application->add(new PolicyReportCommand());
$application->add(new InitialiseDatabaseCommand());

$application->run();
