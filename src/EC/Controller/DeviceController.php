<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DeviceController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render(
            'mydevices/list.twig',
            array(
                'devices' => $app['devices.list'](true)
            )
        );
    }

    public function viewAction(Request $request, Application $app, $deviceId = null)
    {
        $deviceId = $request->get('deviceId');
        $deviceRoute = ($deviceId == null ? '' : '/' . $deviceId);

        return $app['twig']->render(
            'mydevices/viewdevice.twig',
            array(
                'dayStats'      => $app['stats']->fetchDayHighcharts($app, $deviceId),
                'monthStats'    => $app['stats']->fetchMonthHighcharts($app, $deviceId),
                'deviceRoute'   => $deviceRoute
            )
        );
    }
}