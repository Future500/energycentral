<?php

namespace EC\Controller;

use Silex\Application;

class StatsController
{
    public function dayAction(Application $app, $deviceId, $date = null)
    {
        return $app['stats.day.fetch']($deviceId, $date);
    }

    public function monthAction(Application $app, $deviceId, $year = null, $month = null)
    {
        return $app['stats.month.fetch']($deviceId, $month, $year);
    }
}