<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

require_once('includes/class_stats.php'); // class which we use to parse the values for the graph

$app->get('/', function (Request $request) use ($app) {
	$stats = new Stats();
	$stats->fetchDay($app);

	return $app['twig']->render('index.html', Array("testvar" => "none"));
})
->bind('event');
