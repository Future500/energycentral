<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app->before(
    function () use ($app) {
        $sessionLocale = $app['session']->get('locale') == null ? 'en' : $app['session']->get('locale');

        if ($app['translator']->getLocale() != $sessionLocale) {
            $app['translator']->setLocale($sessionLocale);
        }
    }
);

$app->get('/',                           'EC\\Controller\\IndexController::indexAction');
$app->get('/about',                      'EC\\Controller\\AboutController::indexAction');
$app->get('/login',                      'EC\\Controller\\LoginController::indexAction')
    ->bind('login');
$app->get('/admin',                      'EC\\Controller\\AdminController::indexAction');
$app->get('/mydevices',                  'EC\\Controller\\DeviceController::indexAction');
$app->get('/stats/{date}',               'EC\\Controller\\StatsController::dayAction');
$app->get('/stats/{year}/{month}',       'EC\\Controller\\StatsController::monthAction');
$app->get('/date/min_max',               'EC\\Controller\\DateController::minMaxAction');
$app->get('/date/calc/{date}/{format}',  'EC\\Controller\\DateController::calcAction');
$app->post('/conf/lang/{lang}',           'EC\\Controller\\ConfigController::languageAction');