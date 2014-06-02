<?php

namespace EC\Controller;

use EC\Stats\Day;
use EC\Stats\Month;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DeviceController
{
    public function indexAction(Request $request, Application $app)
    {
        $userId             = $app->user()->getId();
        $amountOfDevices    = $app['device']->count($userId);
        $pagination         = $app['pagination']($amountOfDevices, $request->get('p'), 5);
        $devicesToShow      = $app['user']->getDevices($userId, true, $pagination->offset(), $pagination->limit());

        return $app['twig']->render(
            'mydevices/list.twig',
            array(
                'devices'       => $devicesToShow,
                'current_page'  => $pagination->currentPage(),
                'pages'         => $pagination->build()
            )
        );
    }

    public function viewAction(Application $app, $deviceId = null)
    {
        $deviceRoute    = ($deviceId == null ? '' : '/' . $deviceId);

        $daysMinMax     = $app['stats']->minMax('days', $deviceId); // get minimum and maximum day
        $monthsMinMax   = $app['stats']->minMax('months', $deviceId); // get minimum and maximum month

        $hasAccess      = $app['device']->hasAccess($deviceId, $app->user()->getId());

        $dayData    = $app['stats.day.fetch']($deviceId);
        $monthData  = $app['stats.month.fetch']($deviceId);

        return $app['twig']->render(
            'mydevices/viewdevice.twig',
            array(
                'device_route'  => $deviceRoute,
                'device_access' => $hasAccess,
                'day' => array(
                    'stats' => $dayData,
                    'min'   => date('Y-m-d', strtotime($daysMinMax['minimum'])),
                    'max'   => date('Y-m-d', strtotime($daysMinMax['maximum']))
                ),
                'month' => array(
                    'stats' => $monthData,
                    'min'   => date('Y-m', strtotime($monthsMinMax['minimum'])),
                    'max'   => date('Y-m', strtotime($monthsMinMax['maximum']))
                )
            )
        );
    }
}