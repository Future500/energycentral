<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class IndexController
{
    public function indexAction(Request $request, Application $app)
    {
        if (!$app['centralmode']) { // Local mode, we just show the device (subrequest to /mydevice/local)
            return $app->handle(
                Request::create('/mydevices/local', 'GET'),
                HttpKernelInterface::SUB_REQUEST
            );
        }
        return $app['twig']->render('index.twig'); // Non-central mode, we show the homepage with information
    }
}