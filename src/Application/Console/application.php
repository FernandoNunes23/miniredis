<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$client = new \App\Client\Connection();

$application->add(new \App\Application\Console\Command\Set\SetCommand($client));
$application->add(new \App\Application\Console\Command\Set\GetCommand($client));
$application->add(new \App\Application\Console\Command\Set\DeleteCommand($client));
$application->add(new \App\Application\Console\Command\Set\IncrementCommand($client));
$application->add(new \App\Application\Console\Command\Db\DbSizeCommand($client));
$application->add(new \App\Application\Console\Command\Authentication\AuthCommand());

$application->run();