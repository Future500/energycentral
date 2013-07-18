<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

$env = getenv('APPLICATION_ENV') ?: 'prod';
$configFile = __DIR__."/".$env.".json";
$configDefaults = array('root_dir' => dirname(__DIR__));

$app->register(new Igorw\Silex\ConfigServiceProvider($configFile, $configDefaults));
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['config']);
$app->register(new MonologServiceProvider(), $app['config']);

if ($app['debug']) {
    $app->register($p = new WebProfilerServiceProvider(), $app['config']);
    $app->mount('/_profiler', $p);
}

