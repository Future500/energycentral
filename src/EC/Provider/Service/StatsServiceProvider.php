<?php

namespace EC\Provider\Service;

use EC;
use EC\Service\Month as MonthService;
use EC\Service\Day as DayService;
use EC\Service\Stats as StatsService;
use EC\Stats\Day;
use EC\Stats\Month;
use Silex\Application;
use Silex\ServiceProviderInterface;

class StatsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['stats'] = $app->share(function() use ($app) {
            return new StatsService($app['db']);
        });

        $app['stats.day'] = $app->share(function() use ($app) {
            return new DayService($app['db']);
        });

        $app['stats.day.fetch'] = $app->protect(function($deviceId, $date = null, $trimZeroData = false) use ($app) {
            $dayStats   = new Day($app['stats'], $app['stats.day']);
            $dayData    = $dayStats
                ->fetch($deviceId, $date, $trimZeroData)
                ->getEncodedData();

            return $dayData;
        });

        $app['stats.month'] = $app->share(function() use ($app) {
            return new MonthService($app['db']);
        });

        $app['stats.month.fetch'] = $app->protect(function($deviceId, $month = null, $year = null) use ($app) {
            $monthStats = new Month($app['stats.month']);
            $monthData  = $monthStats
                ->fetch($deviceId, $month, $year)
                ->getEncodedData();

            return $monthData;
        });
    }

    public function boot(Application $app)
    {
    }
}