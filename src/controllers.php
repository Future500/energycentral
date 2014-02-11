<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->before(
    function () use ($app) {
        $app['session']->start();
        $locale = $app['session']->get('locale');

        // Restore language that was selected if any
        if($locale != null && $app['translator']->getLocale() != $locale) {
            $app['translator']->setLocale($locale);
        }
    }
);

$app->get(
    '/setLang/{lang}',
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
    '/',
    function (Request $request) use ($app) { // fetch a day (it will use the current day by default)
        return $app['twig']->render(
            'index.twig',
            array(
                'dayStats' => $app['stats']->fetchDayHighcharts($app, ''),
                'monthStats' => $app['stats']->fetchMonthHighcharts($app, date('m')),
                'date' => date('d-m-Y')
            )
        );
    }
);

$app->get(
    '/about',
    function (Request $request) use ($app) {
        return $app['twig']->render('about.twig');
    }
);

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
    '/dateCalc/{date}/{type}/{format}',
    function (Request $request, $date, $type, $format = 'Y-m-d') use ($app) { //date calculations
        if ($type != '-') { // we need to decrease or increase the date
            if (strlen($date) > 7) { // date per day
                $newdate = strtotime(($type == 'inc' ? '+1 day' : '-1 day'), strtotime($date));
            } else { // date per month
                $newdate = strtotime(($type == 'inc' ? '+1 month' : '-1 month'), strtotime($date));
            }
        } else { // just convert the current day to a readable format
            $newdate = strtotime($date);
        }
        return date($format, $newdate); // return new formatted date
    }
);
