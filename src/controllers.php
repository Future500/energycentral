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

$app->mount('/', new EC\Provider\Controller\IndexControllerProvider());
$app->mount('/login', new EC\Provider\Controller\LoginControllerProvider());
$app->mount('/conf', new EC\Provider\Controller\ConfigControllerProvider());
$app->mount('/stats', new EC\Provider\Controller\StatsControllerProvider());
$app->mount('/mydevices', new EC\Provider\Controller\DeviceControllerProvider());
$app->mount('/date', new EC\Provider\Controller\DateControllerProvider());
