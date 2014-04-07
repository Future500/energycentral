<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CopyCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('copy:run')
            ->setDescription('Copy all retrieved data files to central server')
            ->addArgument(
                'user',
                InputArgument::REQUIRED,
                'User that will be used to log in to the remote server'
            )
            ->addArgument(
                'ip',
                InputArgument::REQUIRED,
                'Server IP address to send the CSV files to'
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
        $folder = __DIR__ . '/../../../data';

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

        $commandOutput = array();
        $exitCode = null;

        // Copy each file, quit on failure
        foreach ($files as $filename) {
            exec(
                "scp -o StrictHostKeyChecking=no " . $folder . '/' . $filename . " " . $input->getArgument('user') . "@" . $input->getArgument('ip') . ":current/data",
                $commandOutput,
                $exitCode
            );

            if ($exitCode != 0) { // error occurred
                $output->writeln("SCP exited with code " . $exitCode . ", aborting copy!");
                exit();
            }

            if (!$input->getOption('keepfiles')) {
                unlink($folder . '/' . $filename);
            }
        }
    }
}