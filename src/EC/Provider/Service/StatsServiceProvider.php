<?php

namespace EC\Provider\Service;

use EC;
use EC\Stats\Month;
use EC\Stats\Day;

use Silex\Application;
use Silex\ServiceProviderInterface;

class StatsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['stats'] = $app->share(function() use ($app) {
            return new Device($app['db']);
        });
    }

    public function boot(Application $app)
    {
    }
}