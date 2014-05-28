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
        $deviceId       = $request->get('deviceId');
        $deviceRoute    = ($deviceId == null ? '' : '/' . $deviceId);
        $daysMinMax     = $app['datalayer.minmax']('days', $deviceId); // get minimum and maximum day
        $monthsMinMax   = $app['datalayer.minmax']('months', $deviceId); // get minimum and maximum month

        return $app['twig']->render(
            'mydevices/viewdevice.twig',
            array(
                'device_route'  => $deviceRoute,
                'device_access' => $app['devices.hasaccess']($deviceId),
                'day' => array(
                    'stats' => $app['stats.day']($deviceId)->getEncodedData(),
                    'min'   => date('Y-m-d', strtotime($daysMinMax['minimum'])),
                    'max'   => date('Y-m-d', strtotime($daysMinMax['maximum']))
                ),
                'month' => array(
                    'stats' => $app['stats.month']($deviceId)->getEncodedData(),
                    'min'   => date('Y-m', strtotime($monthsMinMax['minimum'])),
                    'max'   => date('Y-m', strtotime($monthsMinMax['maximum']))
                )
            )
        );
    }
}