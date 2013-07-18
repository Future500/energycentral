<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('EnergyCentral', 'n/a');

$console
    ->register('import:run')
    ->setDescription('Import all available data files')
    ->addArgument(
        'year',
        InputArgument::REQUIRED,
        'What year do you want to import?'
    )
    // ->addOption(
    //    'yell',
    //    null,
    //    InputOption::VALUE_NONE,
    //    'If set, the task will yell in uppercase letters'
    // )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $year = $input->getArgument('year');
            $output->writeln();
    })
;

return $console;
