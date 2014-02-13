<?php

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Igorw\Silex\JsonConfigDriver;
use EC\User\UserProvider;

$env = getenv('APPLICATION_ENV') ?: 'prod';
$configFile = __DIR__."/".$env.".json";
$configDefaults = array('root_dir' => dirname(__DIR__));

$app->register(new Igorw\Silex\ConfigServiceProvider($configFile, $configDefaults, new JsonConfigDriver()));
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['config']);
$app->register(new MonologServiceProvider(), $app['config']);

$app['security.users'] = $app->share(
    function ($app) {
        return new UserProvider($app['db']);
    }
);

$app->register(
    new SecurityServiceProvider(),
    array(
        'security.firewalls' => array(
            'main' => array(
                'pattern'    => '^/',
                'anonymous'  => true,
                'form' => array(
                    'login_path' => '/login',
                    'check_path' => '/login_check'
                ),
                'logout' => array('logout_path' => '/logout'),
                'users' => $app['security.users']
            )
        )
    )
);

$app['security.access_rules'] = array(
    array('^/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
   // array('^/stats', 'ROLE_USER'),
    array('^/admin', 'ROLE_ADMIN')
);

$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array(
        'ROLE_USER',
        'ROLE_ALLOWED_TO_SWITCH'
    ),
);

if ($app['debug']) {
    $app->register($p = new WebProfilerServiceProvider(), $app['config']);
    $app->mount('/_profiler', $p);
}
