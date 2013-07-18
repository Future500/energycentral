<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function (Request $request) use ($app) { // fetch a day (it will use the current day by default)
	return $app['twig']->render('index.html', Array('dayStats' =>  $app['stats']->fetchDay($app, ''),
													'validDate' => true,
													'date' => date('d-m-Y')));
});

$app->get('/{date}', function (Request $request, $date) use ($app) { // fetch a day with a specified date
	return $app['twig']->render('index.html', Array('dayStats' =>  $app['stats']->fetchDay($app, $date),
													'validDate' => $app['stats']->checkDate($date),
													'date' => $date));
});