<?php

namespace EC\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cron:run')
            ->setDescription('Run the cronjob for data retrieval from SMA');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shellResult = shell_exec("scp -o StrictHostKeyChecking=no ./data/*.csv vagrant@192.168.30.50:/home/EnergyCentral/");
        $output->writeln($shellResult);
    }
}
