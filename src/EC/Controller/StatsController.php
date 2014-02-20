<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class StatsController
{
    public function dayAction(Application $app, $deviceId = null, $date = null)
    {
        return $app['stats']->fetchDayHighcharts($app, $deviceId, $date);
    }

    public function monthAction(Application $app, $deviceId = null, $year = null, $month = null)
    {
        return $app['stats']->fetchMonthHighcharts($app, $deviceId, $month, $year);
    }
}