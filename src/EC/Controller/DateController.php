<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DateController
{
    public function minMaxAction(Request $request, Application $app, $deviceId)
    {
        $days = $app['datalayer.minmax']('days', $deviceId); // get minimum and maximum day
        $months = $app['datalayer.minmax']('months', $deviceId); // get minimum and maximum month

        return json_encode(
            array(
                'days' => array(
                    'min' => date('Y-m-d', strtotime($days['minimum'])),
                    'max' => date('Y-m-d', strtotime($days['maximum']))
                ),
                'months' => array(
                    'min' => date('Y-m', strtotime($months['minimum'])),
                    'max' => date('Y-m', strtotime($months['maximum']))
                )
            )
        );
    }

    public function calcAction(Request $request, $date, $format = 'Y-m-d')
    {
        return json_encode(
            array(
                'prev' => date($format, strtotime($format == 'Y-m-d' ? '-1 day' : '-1 month', strtotime($date))),
                'next' => date($format, strtotime($format == 'Y-m-d' ? '+1 day' : '+1 month', strtotime($date)))
            )
        );
    }
}