<?php

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Igorw\Silex\JsonConfigDriver;
use Igorw\Silex\YamlConfigDriver;
use EC\Provider\Security\UserProvider;

$env = getenv('APPLICATION_ENV') ?: 'prod';
$configFile = __DIR__."/".$env.".json";
$configDefaults = array('root_dir' => dirname(__DIR__));

$app->register(new Igorw\Silex\ConfigServiceProvider($configFile, $configDefaults, new JsonConfigDriver()));
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['config']);
$app->register(new MonologServiceProvider(), $app['config']);

$securityFile = $app['centralmode'] ? 'security_central.yml' : 'security_noncentral.yml';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/' . $securityFile, $configDefaults, new YamlConfigDriver()));

$firewalls = $app['security']['firewalls'];
$firewalls['main']['users'] = $app->share(
    function ($app) {
        return new UserProvider($app['db']);
    }
);

$app->register(
    new SecurityServiceProvider(),
    array (
        'security.firewalls'        => $firewalls,
        'security.access_rules'     => $app['security']['access_control'],
        'security.role_hierarchy'   => $app['security']['role_hierarchy']
    )
);

if ($app['debug']) {
    $app->register($p = new WebProfilerServiceProvider(), $app['config']);
    $app->mount('/_profiler', $p);
}