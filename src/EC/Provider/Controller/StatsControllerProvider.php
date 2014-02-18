<?php

namespace EC\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StatsControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/{date}',
            function ($date) use ($app) { // fetch a day or month with a specified date
                return $app['stats']->fetchDayHighcharts($app, $date);
            }
        );

        $controllers->get(
            '/{year}/{month}',
            function ($year, $month) use ($app) { // fetch a month with specified year
                return $app['stats']->fetchMonthHighcharts($app, $month, $year);
            }
        );

        return $controllers;
    }
}
