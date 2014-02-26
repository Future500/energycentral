<?php

namespace EC\Provider\Service;

use EC\Provider;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['datalayer.user'] = $app->protect(
            function ($userId) use ($app) {
                $provider = new Provider\Security\UserProvider($app['db']);
                return $provider->loadUserById($userId);
            }
        );

        $app['datalayer.users'] = $app->protect(
            function ($usernameOnly = false) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select($usernameOnly ? 'u.username' : '*')
                    ->from('user', 'u');

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll($usernameOnly ? \PDO::FETCH_COLUMN : \PDO::FETCH_ASSOC);
            }
        );

        $app['user.getids'] = $app->protect(
            function (array $users) use ($app) {
                $userIds = array();

                foreach ($users as $user) {
                    /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                    $queryBuilder = $app['db']->createQueryBuilder()
                        ->select('userid')
                        ->from('user', 'u')
                        ->where('username = :username')
                        ->setParameter('username', $user);

                    $stmt = $queryBuilder->execute();
                    $userIds[$user] = $stmt->fetchColumn();
                }
                return $userIds;
            }
        );

        $app['datalayer.updatepassword'] = $app->protect(
            function ($userId, $password) use ($app) {
                //$randGenerator = new SecureRandom();

                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder();
                $query = $queryBuilder
                    ->update('user', 'u')
                    ->set('u.password', $queryBuilder->expr()->literal($password))
                    // ->set('u.salt', $queryBuilder->expr()->literal(base64_encode($randGenerator->nextBytes(10))))
                    ->where('u.userid = :userid')
                    ->setParameter(':userid', $userId);

                $stmt = $query->execute();
                return ($stmt == 1);
            }
        );
    }

    public function boot(Application $app)
    {
    }
}