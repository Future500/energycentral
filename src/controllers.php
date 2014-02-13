<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app->before(
    function () use ($app) {
        $app['session']->start();
        $locale = $app['session']->get('locale');

        if ($locale != null && $app['translator']->getLocale() != $locale) {
            $app['translator']->setLocale($locale);
        }
    }
);

$app->post(
    '/setlang/{lang}',
    function (Request $request, $lang) use ($app) {
        $app['session']->start();
        $locale = $app['session']->get('locale');

        if($locale != $lang) { // Set language if not set yet
            $app['translator']->setLocale($lang);
            $app['session']->set('locale', $lang);
        }
        return 1;
    }
);

$app->get(
    '/login',
    function (Request $request) use ($app) {
        if (!$app['centralmode']) { // Running local mode?
            return $app->redirect('/');
        }
        return $app['twig']->render(
            'login.twig',
            array(
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            )
        );
    }
)->bind('login');

$app->get(
    '/',
    function (Request $request) use ($app) { // fetch a day (it will use the current day by default)
        return $app['twig']->render(
            'index.twig',
            array(
                'dayStats' => $app['stats']->fetchDayHighcharts($app),
                'monthStats' => $app['stats']->fetchMonthHighcharts($app)
            )
        );
    }
)->bind('home');

$app->get(
    '/admin',
    function (Request $request) use ($app) {
        return $app['twig']->render('admin.twig');
    }
);

$app->get(
    '/about',
    function (Request $request) use ($app) {
        return $app['twig']->render('about.twig');
    }
)->bind('about');

$app->get(
    '/stats/{date}',
    function (Request $request, $date) use ($app) { // fetch a day or month with a specified date
        return $app['stats']->fetchDayHighcharts($app, $date);
    }
);

$app->get(
    '/stats/{year}/{month}',
    function (Request $request, $year, $month) use ($app) { // fetch a month with specified year
        return $app['stats']->fetchMonthHighcharts($app, $month, $year);
    }
);

$app->get(
    '/val',
    function (Request $request) use ($app) { // used for getting maximum and minimum dates
        $days = $app['db']->fetchAssoc('SELECT MIN(datetime) AS minimum, MAX(datetime) AS maximum FROM daydata');
        $months = $app['db']->fetchAssoc('SELECT MIN(date) AS minimum, MAX(date) AS maximum FROM monthdata');

        return json_encode(array(
            'days' => array(
                'min' => date('Y-m-d', strtotime($days['minimum'])),
                'max' => date('Y-m-d', strtotime($days['maximum']))
            ),
            'months' => array(
                'min' => date('Y-m', strtotime($months['minimum'])),
                'max' => date('Y-m', strtotime($months['maximum']))
            )
        ));
    }
);

$app->get(
    '/datecalc/{date}/{format}',
    function (Request $request, $date, $format = 'Y-m-d') use ($app) { //date calculations
        return json_encode(array(
                'prev' => date($format, strtotime($format == 'Y-m-d' ? '-1 day' : '-1 month', strtotime($date))),
                'next' => date($format, strtotime($format == 'Y-m-d' ? '+1 day' : '+1 month', strtotime($date)))
            )
        );
    }
);
