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
        $shellResult = shell_exec("sudo -u www-data bin/SMAspot -cfgconfig/SMAspot.cfg -ad90 -am60 -finq");
        $output->writeln($shellResult);
    })
;

$console
    ->register('copy:run')
    ->setDescription('Copy all retrieved data files to central server')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $shellResult = shell_exec("sshpass -p 'vagrant' scp ./data/*.csv vagrant@192.168.30.49:/home/EnergyCentral/");
        $output->writeln($shellResult);
    })
;


// APPLICATION_ENV="devel-robbin" ./console import:run --keepfiles 2014

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

                if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{8}.csv/',$filename)) {
                    $table = 'daydata';
                    $column = 'datetime';
                } else if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{6}.csv/',$filename)) {
                    $table = 'monthdata';
                    $column = 'date';
                } else {
                    $output->writeln('<error>Skipped</error>');
                    continue;
                }

                $identifier = substr($filename, 3, strpos($filename, '-', 3) - 3); // device identifier
                $output->write('(' . $identifier . ')');

                $device = $app['db']->executeQuery("SELECT deviceid, accepted FROM device WHERE name = '" . $identifier . "'") // get device id and accepted status
                    ->fetch(PDO::FETCH_ASSOC);

                if (!$device['deviceid']) {
                    $output->writeln(' ... adding new device (no id)');
                    $device['accepted'] = $app['centralmode'] ? 0 : 1; // if we are running in local mode the device does not have to be accepted
                    $app['db']->executeQuery("INSERT INTO device(name, accepted) VALUES('" . $identifier . "', " . $device['accepted'] . ")");
                    $device['deviceid'] = $app['db']->lastInsertId();
                }

                if (!$device['accepted']) {
                    $output->writeln(' ... not accepted');
                    continue;
                }

                if ($input->getOption('dryrun')) {
                    $output->writeln(' ... dryrun');
                    continue;
                }

                if (($handle = fopen($folder.'/'.$filename, "r")) !== false) {
                    while (($data = fgetcsv($handle, 250, ";")) !== false) {
                        $dataSlices = array(
                            'start' => array_slice($data, 0, 1),
                            'end'   => array_slice($data, 1, 2)
                        );

                        $query = "INSERT IGNORE INTO " . $table . "(" . $column . ", deviceid, kWh, kW)
                            VALUES('" . $dataSlices['start'][0] . "'," . $device['deviceid'] . ',' . $dataSlices['end'][0] . ',' . $dataSlices['end'][1] . ")"; // insert row of data

                        $app['db']->executeQuery($query);
                    }
                    fclose($handle);
                }

                if (!$input->getOption('keepfiles')) {
                    $output->write(' ... removing input file');
                    unlink($folder.'/'.$filename);
                }

                $output->writeln(' ... done');
            }
    })
;

return $console;
