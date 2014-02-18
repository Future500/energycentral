<?php

namespace EC\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class DateControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/min_max',
            function (Request $request) use ($app) { // used for getting maximum and minimum dates
                $days = $app['db']->fetchAssoc('SELECT MIN(datetime) AS minimum, MAX(datetime) AS maximum FROM daydata');
                $months = $app['db']->fetchAssoc('SELECT MIN(date) AS minimum, MAX(date) AS maximum FROM monthdata');
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
        );

        $controllers->get(
            '/calc/{date}/{format}',
            function (Request $request, $date, $format = 'Y-m-d') use ($app) { //date calculations
                return json_encode(
                    array(
                        'prev' => date($format, strtotime($format == 'Y-m-d' ? '-1 day' : '-1 month', strtotime($date))),
                        'next' => date($format, strtotime($format == 'Y-m-d' ? '+1 day' : '+1 month', strtotime($date)))
                    )
                );
            }
        );

        return $controllers;
    }
}