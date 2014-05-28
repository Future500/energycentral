<?php

namespace EC\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LoginController
{
    public function indexAction(Request $request, Application $app)
    {
        if (!$app['centralmode']) {
            return $app->redirect('/');
        }

        return $app['twig']->render(
            'login.twig',
            array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            )
        );
    }
}