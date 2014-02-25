<?php

namespace EC\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;

class DeviceServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
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
                    ->setParameter('userid', $userId == null ? $app['security']->getToken()->getUser()->getId() : $userId);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll();
            }
        );

        $app['devices.list.all'] = $app->protect(
            function ($zipcodeOnly = false) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select($zipcodeOnly ? 'zipcode' : '*')
                    ->from('device', 'dev');

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll($zipcodeOnly ? \PDO::FETCH_COLUMN : \PDO::FETCH_ASSOC);
            }
        );

        $app['devices.hasaccess'] = $app->protect(
            function ($deviceId) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('ac.deviceid')
                    ->from('devaccess', 'ac')
                    ->where('userid = :userid')
                    ->setParameter('userid', $app['security']->getToken()->getUser()->getId());

                $stmt = $queryBuilder->execute();
                return in_array($deviceId, $stmt->fetchAll(\PDO::FETCH_COLUMN));
            }
        );
    }

    public function boot(Application $app)
    {
    }
}