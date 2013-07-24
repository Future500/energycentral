<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('EnergyCentral', 'n/a');

$console
    ->register('cron:run')
    ->setDescription('Run the cronjob for data retrieval from SMA')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $output->writeln('<comment>Not implemented yet</comment>');
    })
;

$console
    ->register('import:run')
    ->setDescription('Import all available data files')
    ->addArgument(
        'year',
        InputArgument::REQUIRED,
        'What year do you want to import?'
    )
    ->addOption(
        'dryrun',
        null,
        InputOption::VALUE_NONE,
        'If set, only the filenames will be output'
    )
    ->addOption(
        'keepfiles',
        null,
        InputOption::VALUE_NONE,
        'If set, data files are not deleted after import'
    )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $year = (int) $input->getArgument('year');
            $folder = __DIR__.'/../data/'.$year;

            if (is_dir($folder)) {
                $files = array_filter(scandir($folder), function($filename) {
                    if (strstr($filename,'.csv')) {
                        return true;
                    }
                    return false;
                });
            } else {
                $output->writeln('<error>'.$folder.' could not be found</error>');
                exit();
            }

            foreach ($files as $filename) {
                $output->write('Start processing: ' . $filename . ' ');

                if (preg_match('/ec-[\d]{8}.csv/',$filename)) {
                    $table = 'daydata';
                } elseif(preg_match('/ec-[\d]{6}.csv/',$filename)) {
                    $table = 'monthdata';
                } else {
                    $output->writeln('<error>Skipped</error>');
                    continue;
                }

                if ($input->getOption('dryrun')) {
                    $output->writeln(' ... dryrun');
                    continue;
                }

                $output->write(' ... performing query');
                $query = "LOAD DATA LOCAL INFILE '" . $folder.'/'.$filename . "'
                            REPLACE
                            INTO TABLE " . $table . "
                            FIELDS
                                TERMINATED BY ';'";
                $result = $app['db']->executeQuery($query);

                if (!$input->getOption('keepfiles')) {
                    $output->write(' ... removing input file');
                    unlink($folder.'/'.$filename);
                }

                $output->writeln(' ... done');
            }
    })
;

return $console;
