<?php

namespace EC\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ConfigControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->post(
            '/lang/{lang}',
            function ($lang) use ($app) {
                $sessionLocale = $app['session']->get('locale');

                if ($sessionLocale != $lang) { // Set language if not set yet
                    $app['translator']->setLocale($lang);
                    $app['session']->set('locale', $lang);
                }
                return true;
            }
        );

        $controllers->post(
            '/device/{device}',
            function ($device) use ($app) {
                if ($app['session']->get('device') != $device) { // Set device if not set yet
                    $app['session']->set('locale', $device);
                }
                return true;
            }
        );

        return $controllers;
    }
}
