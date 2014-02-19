<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DeviceController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render(
            'mydevice.twig',
            array(
                'dayStats'   => $app['stats']->fetchDayHighcharts($app),
                'monthStats' => $app['stats']->fetchMonthHighcharts($app)
            )
        );
    }
}