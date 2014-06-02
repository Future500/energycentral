<?php

namespace EC\Provider\Service;

use EC\Service\Device;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DeviceServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['device'] = $app->share(function() use ($app) {
            return new Device($app['db']);
        });
    }

    public function boot(Application $app)
    {
    }
}