<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DeviceController
{
    public function indexAction(Request $request, Application $app)
    {
        $pagination = $app['pagination']($app['devices.count']($app->user()->getId()), $request->get('p'), 5);

        return $app['twig']->render(
            'mydevices/list.twig',
            array(
                'devices'       => $app['devices.list'](true, $app->user()->getId(), $pagination->offset(), $pagination->limit()),
                'current_page'  => $pagination->currentPage(),
                'pages'         => $pagination->build()
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
                'dayStats'      => $app['stats']->fetchDayHighcharts($app, null, $deviceId),
                'monthStats'    => $app['stats']->fetchMonthHighcharts($app, null, $deviceId),
                'device_route'   => $deviceRoute,
                'device_access' => $app['devices.hasaccess']($deviceId)
            )
        );
    }
}