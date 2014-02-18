<?php

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Igorw\Silex\JsonConfigDriver;
use Igorw\Silex\YamlConfigDriver;
use EC\User\UserProvider;

$env = getenv('APPLICATION_ENV') ?: 'prod';
$configFile = __DIR__."/".$env.".json";
$configDefaults = array('root_dir' => dirname(__DIR__));

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/security.yml', $configDefaults, new YamlConfigDriver()));
$app->register(new Igorw\Silex\ConfigServiceProvider($configFile, $configDefaults, new JsonConfigDriver()));
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['config']);
$app->register(new MonologServiceProvider(), $app['config']);

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
