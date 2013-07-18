<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('EnergyCentral', 'n/a');

$console
    ->register('demo:greet')
    ->setDescription('Greet someone')
    ->addArgument(
        'year',
        InputArgument::OPTIONAL,
        'What year do you want to '
    )
    // ->addOption(
    //    'yell',
    //    null,
    //    InputOption::VALUE_NONE,
    //    'If set, the task will yell in uppercase letters'
    // )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $name = $input->getArgument('year');
            if ($name) {
                $text = 'Hello '.$name;
            } else {
                $text = 'Hello';
            }

            if ($input->getOption('yell')) {
                $text = strtoupper($text);
            }

            $output->writeln($text);
    })
;

return $console;
