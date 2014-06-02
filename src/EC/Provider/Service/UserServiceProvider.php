<?php

namespace EC\Provider\Service;

use EC\Entity\User as UserEntity;
use EC\Provider;
use EC\Service\User;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['user'] = $app->share(
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

        $app['user.encode_password'] = $app->protect(
            function (UserEntity $user, $password) use ($app) {
                $randGenerator = new SecureRandom();

                $encoder       = $app['security.encoder_factory']->getEncoder($user);
                $salt          = base64_encode($randGenerator->nextBytes(25));

                $result = array(
                    'password' => $encoder->encodePassword($password, $salt),
                    'salt'     => $salt
                );

                return $result;
            }
        );
    }

    public function boot(Application $app)
    {
    }
}