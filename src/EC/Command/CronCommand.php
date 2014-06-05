<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\Process;

class CronCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:run')
            ->setDescription('Run the cronjob for data retrieval from SMA')
            ->addArgument(
                'homedir',
                InputArgument::REQUIRED,
                'The path to the current user his home directory (absolute path, no trailing slash)'
            )
            ->addOption(
                'logpath',
                null,
                InputOption::VALUE_REQUIRED,
                'The path of the logfile to which the SMAspot output will be written. Leave empty to disable.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $homeDir        = $input->getArgument('homedir');
        $smaSpotBinary  = $homeDir . '/current/bin/SMAspot';

        // Check if SMAspot binary exists
        if (!file_exists($smaSpotBinary)) {
            throw new FileNotFoundException('SMAspot binary could not be found!');
        }

        $command = $smaSpotBinary . ' -cfg' . $homeDir . '/current/config/SMAspot.cfg -ad90 -am60 -finq';

        // Append the logging parameter to the command if the logpath is specified
        $logPath = $input->getOption('logpath');

        if (!empty($logPath)) {
            $command .= ' > ' . $logPath . ' 2>&1';
        }

        $process = new Process($command);
        $process->run();

        echo $process->getOutput();
    }
}