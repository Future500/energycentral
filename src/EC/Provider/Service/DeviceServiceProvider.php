<?php

namespace EC\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;

class DeviceServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        /*
         * Update the device access table for a specific user.
         * The function adds new devices or removes devices that the user should no longer have access to.
         */
        $app['devices.update'] = $app->protect(
            function ($userId, array $addedDevices, array $removedDevices) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder();

                foreach ($addedDevices as $deviceId) { // add new devices
                    $queryBuilder
                        ->insert('devaccess', 'dev')
                        ->values(
                            array(
                                'deviceid'  => $deviceId,
                                'userid'    => $userId
                            )
                        )
                        ->execute();
                }

                foreach ($removedDevices as $deviceId) { // remove old devices if needed
                    $queryBuilder
                        ->delete('devaccess')
                        ->where('deviceid = :deviceid AND userid = :userid')
                        ->setParameters(
                            array(
                                'deviceid' => $deviceId,
                                'userid' => $userId
                            )
                        )
                        ->execute();
                }
            }
        );

        $app['devices.count'] = $app->protect(
            function () use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('COUNT(*)')
                    ->from('device', 'dev');

                $stmt = $queryBuilder->execute();
                return $stmt->fetchColumn();
            }
        );

        /*
         * Update the device access table.
         * The function adds new users or removes users from a device.
         */
        $app['devices.update_users'] = $app->protect(
            function ($deviceId, array $addedUsers, array $removedUsers) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder();

                foreach ($addedUsers as $userId) { // add new users
                    $queryBuilder
                        ->insert('devaccess', 'dev')
                        ->values(
                            array(
                                'deviceid'  => $deviceId,
                                'userid'    => $userId
                            )
                        )
                        ->execute();
                }

                foreach ($removedUsers as $userId) { // remove old users if needed
                    $queryBuilder
                        ->delete('devaccess')
                        ->where('deviceid = :deviceid AND userid = :userid')
                        ->setParameters(
                            array(
                                'deviceid' => $deviceId,
                                'userid' => $userId
                            )
                        )
                        ->execute();
                }
            }
        );

        /*
         * Extracts the zipcode from a device array
         */
        $app['devices.getzipcodes'] = $app->protect(
            function (array $devices) {
                array_walk(
                    $devices,
                    function (&$item) {
                        $item = $item['zipcode'];
                    }
                );
                return $devices;
            }
        );

        /*
         * Returns the id for every device in the array
         */
        $app['devices.getids'] = $app->protect(
            function (array $devices) use ($app) {
                $deviceIds = array();

                foreach ($devices as $device) {
                    /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                    $queryBuilder = $app['db']->createQueryBuilder()
                        ->select('deviceid')
                        ->from('device', 'dev')
                        ->where('zipcode = :zipcode')
                        ->setParameter('zipcode', $device);

                    $stmt = $queryBuilder->execute();
                    $deviceIds[$device] = $stmt->fetchColumn();
                }
                return $deviceIds;
            }
        );

        /*
         * List all devices a user has access to, including the zipcode if needed
         */
        $app['devices.list'] = $app->protect(
            function ($withDetails = false, $userId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder();

                if ($withDetails) { // include zipcode and house number?
                    $queryBuilder
                        ->select('dev.*')
                        ->from('devaccess', 'ac')
                        ->innerJoin('ac', 'device', 'dev', 'ac.deviceid = dev.deviceid');
                } else {
                    $queryBuilder
                        ->select('deviceid')
                        ->from('devaccess', 'dev');
                }

                $queryBuilder
                    ->where('userid = :userid')
                    ->setParameter('userid', $userId == null ? $app->user()->getId() : $userId);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll();
            }
        );

        /*
         * List all users that belong to a specific device ID
         */
        $app['devices.list_users'] = $app->protect(
            function ($deviceId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('u.username')
                    ->from('devaccess', 'a')
                    ->innerJoin('a', 'user', 'u', 'a.userid = u.userid')
                    ->where('a.deviceid = :deviceid')
                    ->setParameter('deviceid', $deviceId);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll(\PDO::FETCH_COLUMN);
            }
        );

        /*
         * List all devices, including zipcode if needed
         */
        $app['devices.list.all'] = $app->protect(
            function ($zipcodeOnly = false, $offset = null, $perPage = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select($zipcodeOnly ? 'zipcode' : '*')
                    ->from('device', 'dev');

                if (!$zipcodeOnly && ($offset != null || $perPage != null)) {
                    $queryBuilder
                        ->setFirstResult($offset)
                        ->setMaxResults($perPage);
                }

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll($zipcodeOnly ? \PDO::FETCH_COLUMN : \PDO::FETCH_ASSOC);
            }
        );

        /*
         * Checks if a user has access to a specific device
         */
        $app['devices.hasaccess'] = $app->protect(
            function ($deviceId, $userId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('ac.deviceid')
                    ->from('devaccess', 'ac')
                    ->where('userid = :userid')
                    ->setParameter('userid', $userId == null ? $app->user()->getId() : $userId);

                $stmt = $queryBuilder->execute();
                return in_array($deviceId, $stmt->fetchAll(\PDO::FETCH_COLUMN));
            }
        );
    }

    public function boot(Application $app)
    {
    }
}