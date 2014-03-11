<?php

namespace EC\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('copy:run')
            ->setDescription('Copy all retrieved data files to central server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shellResult = shell_exec("sudo -u www-data bin/SMAspot -cfgconfig/SMAspot.cfg -ad90 -am60 -finq");
        $output->writeln($shellResult);
    }
}
