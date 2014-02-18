<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app->before(
    function () use ($app) {
        $sessionLocale = $app['session']->get('locale') == null ? 'en' : $app['session']->get('locale');

        if ($app['translator']->getLocale() != $sessionLocale) {
            $app['translator']->setLocale($sessionLocale);
        }
    }
);

$app->mount('/', new EC\Provider\IndexControllerProvider());
$app->mount('/login', new EC\Provider\LoginControllerProvider());
$app->mount('/conf', new EC\Provider\ConfigControllerProvider());
$app->mount('/stats', new EC\Provider\StatsControllerProvider());
$app->mount('/mydevices', new EC\Provider\DeviceControllerProvider());
$app->mount('/date', new EC\Provider\DateControllerProvider());
