<?php

namespace EC\Command;

use Doctrine\DBAL\Connection;
use EC\Service\Device;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var \EC\Service\Device
     */
    protected $deviceService;

    /**
     * @var bool
     */
    protected $centralMode;

    /**
     * @param Connection $db
     * @param Device $deviceService
     * @param bool $centralMode
     */
    public function __construct(Connection $db, Device $deviceService, $centralMode = false)
    {
        parent::__construct();

        $this->db               = $db;
        $this->deviceService    = $deviceService;
        $this->centralMode      = $centralMode;
    }

    protected function configure()
    {
        $this
            ->setName('import:run')
            ->setDescription('Import all available data files')
            ->addOption(
                'dryrun',
                null,
                InputOption::VALUE_NONE,
                'If set, only the filenames will be output'
            )
            ->addOption(
                'removefiles',
                null,
                InputOption::VALUE_NONE,
                'If set, data files are not kept after import'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folder = __DIR__ . '/../../../data/';

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

        echo "Importing data ...\n";

        foreach ($files as $filename) {
            $output->write('Start processing: ' . $filename . ' ');

            if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{8}.csv/', $filename)) {
                $table  = 'daydata';
                $column = 'datetime';
            } else if (preg_match('/ec-[a-zA-Z0-9]{4,25}-[\d]{6}.csv/', $filename)) {
                $table  = 'monthdata';
                $column = 'date';
            } else {
                $output->writeln('<error>Skipped</error>');
                continue;
            }

            $identifier = substr($filename, 3, strpos($filename, '-', 3) - 3); // device identifier
            $output->write('(' . $identifier . ')');

            // Get the accepted status
            $deviceAccepted = $this->deviceService->getAcceptedStatus($identifier);

            if ($deviceAccepted === false) {
                $output->writeln(' ... adding new device (no accepted status)');

                // In local mode the device does not have to be accepted for the import to run
                if (!$this->centralMode) {
                    $deviceAccepted = true;
                }

                // Add the new device and save the device id for import
                $deviceId = $this->deviceService->addNew($identifier, $deviceAccepted);
            } else {
                // Retrieve the device ID of the existing device by using the device name
                $deviceId = $this->deviceService->getSingleId($identifier);
            }

            if ($this->centralMode && !$deviceAccepted) {
                $output->writeln(' ... not accepted');
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
                        'end'   => array_slice($data, 1, 2)
                    );

                    $query = "INSERT IGNORE INTO " .
                                $table . "(" . $column . ", deviceid, kWh, kW)" .
                             "VALUES('" .
                                $dataSlices['start'][0] . "'," .
                                $deviceId . ',' .
                                $dataSlices['end'][0] . ',' .
                                $dataSlices['end'][1] .
                             ")"; // insert row of data

                    $this->db->executeQuery($query);
                }

                fclose($handle);
            }

            if ($input->getOption('removefiles')) {
                echo "Removing files from import folder ...\n";
                unlink($folder . '*.csv');
            }

            $output->writeln(' ... done');
        }

        echo "Import complete.\n";
    }
}
