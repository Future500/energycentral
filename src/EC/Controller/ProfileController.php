<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ProfileController
{
    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render(
            'profile.twig'
        );
    }
}