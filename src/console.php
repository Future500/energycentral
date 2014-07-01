<?php

use Symfony\Component\Console\Application;

$console = new Application('EnergyCentral', 'n/a');
$console->add(new EC\Command\CronCommand());
$console->add(new EC\Command\CopyCommand());
$console->add(new EC\Command\ImportCommand($app['db'], $app['device'], $app['centralmode'])); // APPLICATION_ENV="devel-robbin" ./console import:run
$console->run();

return $console;
