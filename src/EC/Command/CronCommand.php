<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $homeDir = $input->getArgument('homedir');
        $command = $homeDir . '/current/bin/SMAspot -cfg' . $homeDir . '/current/config/SMAspot.cfg -ad90 -am60 -finq > ' . $homeDir . '/cron_smaspot.log 2>&1';

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) { // error occurred
            throw new \RuntimeException($process->getErrorOutput());
            exit;
        }

        echo $process->getOutput();
    }
}