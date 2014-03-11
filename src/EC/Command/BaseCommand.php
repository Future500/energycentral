<?php

namespace EC\Command;

use Symfony\Component\Console\Command\Command;
use EC\Application;

class BaseCommand extends Command
{
    protected $app;

    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }
} 