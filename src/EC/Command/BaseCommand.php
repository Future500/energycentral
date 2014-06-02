<?php

namespace EC\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var bool
     */
    protected $centralMode;

    /**
     * @param Connection $db
     * @param bool $centralMode
     */
    public function __construct(Connection $db, $centralMode = false)
    {
        parent::__construct();

        $this->db          = $db;
        $this->centralMode = $centralMode;
    }
}