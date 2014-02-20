<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AdminController
{
    public function usersAction(Request $request, Application $app)
    {
        return $app['twig']->render('admin/users.twig');
    }
}