<?php

namespace EC\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class LoginControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/',
            function (Request $request) use ($app) {
                if (!$app['centralmode']) {
                    return $app->redirect('/');
                }
                return $app['twig']->render(
                    'login.twig',
                    array(
                        'error' => $app['security.last_error']($request),
                        'last_username' => $app['session']->get('_security.last_username'),
                    )
                );
            }
        )->bind('login');

        return $controllers;
    }
}