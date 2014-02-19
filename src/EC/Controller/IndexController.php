<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    public function indexAction(Request $request, Application $app)
    {
        if (!$app['centralmode']) { // Local mode, we just show the device (subrequest to /mydevice)
            return $app->handle(
                Request::create('/mydevices', 'GET'),
                HttpKernelInterface::SUB_REQUEST
            );
        }
        return $app['twig']->render('index.twig'); // Non-central mode, we show the homepage with information
    }
}