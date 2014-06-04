<?php

use Symfony\Component\Console\Application;

$console = new Application('EnergyCentral', 'n/a');
$console->add(new EC\Command\CronCommand($app['db'], $app['centralmode']));
$console->add(new EC\Command\CopyCommand($app['db'], $app['centralmode']));
$console->add(new EC\Command\ImportCommand($app['db'], $app['centralmode'])); // APPLICATION_ENV="devel-robbin" ./console import:run --keepfiles 2013
$console->run();

return $console;
