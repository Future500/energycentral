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

        $app['user.count'] = $app->protect(
            function () use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('COUNT(*)')
                    ->from('user', 'u');

                $stmt = $queryBuilder->execute();
                return $stmt->fetchColumn();
            }
        );

        $app['datalayer.users'] = $app->protect(
            function ($usernameOnly = false, $offset = null, $limit = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select($usernameOnly ? 'u.username' : '*')
                    ->from('user', 'u');

                if ($offset != null || $limit != null) {
                    $queryBuilder
                        ->setFirstResult($offset)
                        ->setMaxResults($limit);
                }

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

        $app['user.generatesalt'] = $app->protect(
            function ($length = 25) use ($app) {
                $randGenerator = new SecureRandom();
                return base64_encode($randGenerator->nextBytes($length));
            }
        );

        $app['datalayer.updatepassword'] = $app->protect(
            function ($userId, $password) use ($app) {
                $salt = $app['user.generatesalt']();
                $encoder = $app['security.encoder_factory']->getEncoder($app['datalayer.user']($userId));

                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder();
                $query = $queryBuilder
                    ->update('user', 'u')
                    ->set(
                        'u.password',
                        $queryBuilder->expr()->literal(
                            $encoder->encodePassword($password, $salt)
                        )
                    )
                    ->set(
                        'u.salt',
                        $queryBuilder->expr()->literal($salt)
                    )
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