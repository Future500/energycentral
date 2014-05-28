<?php

namespace EC\Controller;

use Silex\Application;

class StatsController
{
    public function dayAction(Application $app, $deviceId = null, $date = null)
    {
        return $app['stats.day']($deviceId, $date)->getEncodedData();
    }

    public function monthAction(Application $app, $deviceId = null, $year = null, $month = null)
    {
        return $app['stats.month']($deviceId, $month, $year)->getEncodedData();
    }
}