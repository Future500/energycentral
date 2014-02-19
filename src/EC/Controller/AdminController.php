<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AdminController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render('admin.twig');
    }
}