<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AboutController
{
    public function indexAction(Application $app)
    {
        return $app['twig']->render('about.twig');
    }
}