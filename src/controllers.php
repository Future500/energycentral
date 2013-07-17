<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function (Request $request) use ($app) {
	return $app['twig']->render('index.html', Array("dayStats" =>  $app['stats']->fetchDay($app)));
});

$app->get('/{date}', function (Request $request, $date) use ($app) {
	return $app['twig']->render('index.html', Array("dayStats" =>  $app['stats']->fetchDay($app, $date)));
});