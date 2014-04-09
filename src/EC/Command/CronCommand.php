<?php

namespace EC\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:run')
            ->setDescription('Run the cronjob for data retrieval from SMA');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = "/home/energycentral/current/bin/SMAspot -cfg/home/energycentral/current/config/SMAspot.cfg -ad90 -am60 -finq";

        $shellResult = shell_exec($cmd . " > /home/energycentral/cron_smaspot.log 2>&1");
        $output->writeln($shellResult);
    }
}