<?php

namespace EC\Provider\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;

class DeviceServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['datalayer.add_devices'] = $app->protect(
            function ($userId, Array $devices) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                /*$queryBuilder = $app['db']->createQueryBuilder();

                foreach ($devices as $device) {
                    $queryBuilder
                        ->insert('devaccess')
                        ->values(
                            array(
                                'deviceid' => $device[''];
                            )
                        );
                }

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll(); */
            }
        );

        $app['devices.getzipcodes'] = $app->protect(
            function (Array $devices) use ($app) {
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
            function (Array $devices) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                array_walk(
                    $devices,
                    function (&$item) {
                        die($item);
                    }
                );

                //   $stmt = $queryBuilder->execute();
                //  return $stmt->fetchAll();
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
                return $stmt->fetchAll();
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