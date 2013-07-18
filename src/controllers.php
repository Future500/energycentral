<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function (Request $request) use ($app) { // fetch a day (it will use the current day by default)
	return $app['twig']->render('index.html', Array('dayStats' =>  $app['stats']->fetchDayHighcharts($app, ''),
													'validDate' => true,
													'date' => date('d-m-Y')));
});

$app->get('/{date}', function (Request $request, $date) use ($app) { // fetch a day or month with a specified date
	if(strlen($date) > 7) { // we've chosen a day (ex: 2013-04-07)
		return $app['twig']->render('index.html', Array('dayStats' =>  $app['stats']->fetchDayHighcharts($app, $date),
													'validDate' => $app['stats']->checkDate($date),
													'date' => $date));
	} else { // we've chosen a month (ex: 2013-04)
	
	}
});