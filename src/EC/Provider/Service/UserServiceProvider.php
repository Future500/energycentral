<?php

namespace EC\Provider\Service;

use EC\Provider;
use EC\Service\User;
use Silex\Application;
use Silex\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['user'] = $app->protect(
            function () use ($app) {
                return new User($app['db']);
            }
        );

        $app['user.load'] = $app->protect(
            function ($userId) use ($app) {
                $provider = new Provider\Security\UserProvider($app['db']);
                return $provider->loadUserById($userId);
            }
        );
    }

    public function boot(Application $app)
    {
    }
}