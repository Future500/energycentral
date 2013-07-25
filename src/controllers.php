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

/*
        $app['session']->start();
        $locale = $app['session']->get('locale');

        if($locale != $lang) {
            $app['translator']->setLocale($lang);
            $app['session']->set('locale', $lang);
        }
    */

$app->get(
    '/setLang/{lang}',
    function (Request $request, $lang) use ($app) { // fetch a day (it will use the current day by default)
        $app['session']->start();
        $locale = $app['session']->get('locale');

        if($locale != $lang) {
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
    '/{date}',
    function (Request $request, $date) use ($app) { // fetch a day or month with a specified date
        return $app['stats']->fetchDayHighcharts($app, $date);
    }
);

$app->get(
    '/{year}/{month}',
    function (Request $request, $year, $month) use ($app) { // fetch a month with specified year
        return $app['stats']->fetchMonthHighcharts($app, $month, $year);
    }
);

$app->get(
    '/val/{type}/{dateType}',
    function (Request $request, $type, $dateType) use ($app) { // used for getting maximum and minimum dates
        $tableName = ($dateType == 'month' ? 'monthdata' : 'daydata');
        $columnName = ($dateType == 'month' ? 'date' : 'datetime');

        if ($type == 'min') {
            $retn = $app['db']->fetchColumn(
                'SELECT MIN(' . $columnName . ') FROM ' . $tableName
            );
        } else if ($type == 'max') {
            $retn = $app['db']->fetchColumn(
                'SELECT MAX(' . $columnName . ') FROM ' . $tableName
            );
        }
        return date(($dateType == 'month' ? 'Y-m' : 'Y-m-d'), strtotime($retn));
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
