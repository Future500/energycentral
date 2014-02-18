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

$app->mount('/', new EC\Controller\IndexControllerProvider());
$app->mount('/login', new EC\Controller\LoginControllerProvider());
$app->mount('/conf', new EC\Controller\ConfigControllerProvider());
$app->mount('/stats', new EC\Controller\StatsControllerProvider());
$app->mount('/mydevices', new EC\Controller\DeviceControllerProvider());
$app->mount('/date', new EC\Controller\DateControllerProvider());
