<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class StatsController
{
    public function dayAction(Application $app, $date)
    {
        return $app['stats']->fetchDayHighcharts($app, $date);
    }

    public function monthAction(Application $app, $year, $month)
    {
        return $app['stats']->fetchMonthHighcharts($app, $month, $year);
    }
}