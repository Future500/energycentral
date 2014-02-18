<?php

namespace EC\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class DeviceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/',
            function () use ($app) { // fetch a day (it will use the current day by default)
                return $app['twig']->render(
                    'mydevice.twig',
                    array(
                        'dayStats'   => $app['stats']->fetchDayHighcharts($app),
                        'monthStats' => $app['stats']->fetchMonthHighcharts($app)
                    )
                );
            }
        )->bind('mydevice');
       //->before($beforeAccess);

        return $controllers;
    }
}