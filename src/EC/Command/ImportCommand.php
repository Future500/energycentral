<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('import:run')
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = (int)$input->getArgument('year');
        $folder = __DIR__ . '/../../../data/' . $year;

        if (is_dir($folder)) {
            $files = array_filter(scandir($folder), function ($filename) {
                if (strstr($filename, '.csv')) {
                    return true;
                }
                return false;
            });
        } else {
            $output->writeln('<error>' . $folder . ' could not be found</error>');
            exit();
        }

        foreach ($files as $filename) {
            $output->write('Start processing: ' . $filename . ' ');

            if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{8}.csv/', $filename)) {
                $table = 'daydata';
                $column = 'datetime';
            } else if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{6}.csv/', $filename)) {
                $table = 'monthdata';
                $column = 'date';
            } else {
                $output->writeln('<error>Skipped</error>');
                continue;
            }

            $identifier = substr($filename, 3, strpos($filename, '-', 3) - 3); // device identifier
            $output->write('(' . $identifier . ')');

            $device = $this->app['db']->executeQuery("SELECT deviceid, accepted FROM device WHERE name = '" . $identifier . "'") // get device id and accepted status
                ->fetch(\PDO::FETCH_ASSOC);

            if (!$device['deviceid']) {
                $output->writeln(' ... adding new device (no id)');
                $device['accepted'] = $this->app['centralmode'] ? 0 : 1; // if we are running in local mode the device does not have to be accepted
                $this->app['db']->executeQuery("INSERT INTO device(name, accepted) VALUES('" . $identifier . "', " . $device['accepted'] . ")");
                $device['deviceid'] = $this->app['db']->lastInsertId();
            }

            if (!$device['accepted']) {
                $output->writeln(' <error>... not accepted</error>');
                continue;
            }

            if ($input->getOption('dryrun')) {
                $output->writeln(' ... dryrun');
                continue;
            }

            if (($handle = fopen($folder . '/' . $filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 250, ";")) !== false) {
                    $dataSlices = array(
                        'start' => array_slice($data, 0, 1),
                        'end' => array_slice($data, 1, 2)
                    );

                    $query = "INSERT IGNORE INTO " . $table . "(" . $column . ", deviceid, kWh, kW)
                            VALUES('" . $dataSlices['start'][0] . "'," . $device['deviceid'] . ',' . $dataSlices['end'][0] . ',' . $dataSlices['end'][1] . ")"; // insert row of data

                    $this->app['db']->executeQuery($query);
                }
                fclose($handle);
            }

            if (!$input->getOption('keepfiles')) {
                $output->write(' ... removing input file');
                unlink($folder . '/' . $filename);
            }

            $output->writeln(' ... done');
        }
    }
}
