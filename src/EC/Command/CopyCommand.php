<?php

namespace EC\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CopyCommand extends Command
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
        $dataFolder = __DIR__ . '/../../../data';
        $batchFile  = __DIR__ . '/../../../csv_batch.txt';

        if (!is_dir($dataFolder)) {
            $output->writeln('<error>' . $dataFolder . ' could not be found</error>');
            exit;
        }

        $command = 'sftp -o StrictHostKeyChecking=no -b ' . $batchFile . ' ' . $input->getArgument('user') . "@" . $input->getArgument('ip');

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) { // error occurred
            throw new \RuntimeException($process->getErrorOutput());
        }

        if (!$input->getOption('keepfiles')) {
            echo "Removing CSV files ...\n";
            unlink($dataFolder . '/*.csv');
        }

        echo $process->getOutput();
    }
}