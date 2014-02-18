<?php

namespace EC\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class IndexControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/',
            function () use ($app) {
                if (!$app['centralmode']) { // Local mode, we just show the device (subrequest to /mydevice)
                    return $app->handle(
                        Request::create('/mydevice', 'GET'),
                        HttpKernelInterface::SUB_REQUEST
                    );
                }
                return $app['twig']->render('index.twig'); // Non-central mode, we show the homepage with information
            }
        )->bind('home');

        $controllers->get(
            '/about',
            function () use ($app) {
                return $app['twig']->render('about.twig');
            }
        )->bind('about');

        return $controllers;
    }
}
