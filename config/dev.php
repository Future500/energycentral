<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array (
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'future500_logcentral',
        'user'      => 'future500',
        'password'  => '46RhuBv8KFtz',
        'charset'   => 'utf8',
    )
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../silex.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../cache/profiler',
));
$app->mount('/_profiler', $p);
