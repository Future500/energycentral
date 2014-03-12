<?php

use Symfony\Component\Console\Application;

$console = new Application('EnergyCentral', 'n/a');
$console->add(new EC\Command\CronCommand($app));
$console->add(new EC\Command\CopyCommand($app));
$console->add(new EC\Command\ImportCommand($app)); // APPLICATION_ENV="devel-robbin" ./console import:run --keepfiles 2013
$console->run();

return $console;
