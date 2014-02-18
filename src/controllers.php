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

$beforeAccess = function (Request $request, Silex\Application $app) { // Executed for every main page except home, login and about
    if ($app['centralmode'] && !$app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
        throw new AccessDeniedException(); // We're in central mode and we are not logged in (while we need to be), throw an exception
    }
};

$app->get(
    '/',
    function () use ($app) {
        if (!$app['centralmode']) { // Local mode, we just show the device (subrequest to /mydevice)
            return $app->handle(
                Request::create('/mydevice', 'GET'),
                HttpKernelInterface::SUB_REQUEST
            );
        }
        return $app['twig']->render('index.twig'); // Non-central mode, we show the homepage with information
    }
)->bind('home');

$app->get(
    '/about',
    function () use ($app) {
        return $app['twig']->render('about.twig');
    }
)->bind('about');

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

$app->post(
    '/setlang/{lang}',
    function ($lang) use ($app) {
        $sessionLocale = $app['session']->get('locale');

        if ($sessionLocale != $lang) { // Set language if not set yet
            $app['translator']->setLocale($lang);
            $app['session']->set('locale', $lang);
        }
        return 1;
    }
);

$app->post(
    '/setdevice/{device}',
    function ($device) use ($app) {
        if ($app['session']->get('device') != $device) { // Set device if not set yet
            $app['session']->set('locale', $device);
        }
    }
);

$app->get(
    '/mydevice',
    function () use ($app) { // fetch a day (it will use the current day by default)
        return $app['twig']->render(
            'mydevice.twig',
            array(
                'dayStats'   => $app['stats']->fetchDayHighcharts($app),
                'monthStats' => $app['stats']->fetchMonthHighcharts($app)
            )
        );
    }
)
->bind('mydevice')
->before($beforeAccess);

$app->get(
    '/stats/{date}',
    function ($date) use ($app) { // fetch a day or month with a specified date
        return $app['stats']->fetchDayHighcharts($app, $date);
    }
)->before($beforeAccess);

$app->get(
    '/stats/{year}/{month}',
    function ($year, $month) use ($app) { // fetch a month with specified year
        return $app['stats']->fetchMonthHighcharts($app, $month, $year);
    }
)->before($beforeAccess);

$app->get(
    '/val',
    function (Request $request) use ($app) { // used for getting maximum and minimum dates
        $days = $app['db']->fetchAssoc('SELECT MIN(datetime) AS minimum, MAX(datetime) AS maximum FROM daydata');
        $months = $app['db']->fetchAssoc('SELECT MIN(date) AS minimum, MAX(date) AS maximum FROM monthdata');
        return json_encode(
            array(
                'days' => array(
                    'min' => date('Y-m-d', strtotime($days['minimum'])),
                    'max' => date('Y-m-d', strtotime($days['maximum']))
                ),
                'months' => array(
                    'min' => date('Y-m', strtotime($months['minimum'])),
                    'max' => date('Y-m', strtotime($months['maximum']))
                )
            )
        );
    }
)->before($beforeAccess);

$app->get(
    '/datecalc/{date}/{format}',
    function (Request $request, $date, $format = 'Y-m-d') use ($app) { //date calculations
        return json_encode(
            array(
                'prev' => date($format, strtotime($format == 'Y-m-d' ? '-1 day' : '-1 month', strtotime($date))),
                'next' => date($format, strtotime($format == 'Y-m-d' ? '+1 day' : '+1 month', strtotime($date)))
            )
        );
    }
)->before($beforeAccess);
