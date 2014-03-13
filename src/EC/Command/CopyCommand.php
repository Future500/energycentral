<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shellResult = shell_exec("scp -o StrictHostKeyChecking=no ./data/*.csv " . $input->getArgument('user') . "@" . $input->getArgument('ip') . ":/home/EnergyCentral/");
        $output->writeln($shellResult);
    }
}