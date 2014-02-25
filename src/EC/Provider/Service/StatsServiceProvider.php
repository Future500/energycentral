<?php

namespace EC\Provider\Service;

use EC;
use Silex\Application;
use Silex\ServiceProviderInterface;

class StatsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['stats'] = $app->share(
            function () {
                return new EC\Stats();
            }
        );

        $app['datalayer.minmax'] = $app->protect(
            function ($type, $deviceId = null) use ($app) {
                if (!in_array($type, array('days', 'months'))) {
                    throw new \InvalidArgumentException('Invalid type passed to datalayer.minmax');
                }

                $dateType = ($type == 'days' ? 'datetime' : 'date');
                $table = ($type == 'days' ? 'daydata' : 'monthdata');

                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('MIN(' . $dateType . ') AS minimum', 'MAX(' . $dateType . ') AS maximum')
                    ->from($table, 'd');

                if ($deviceId != null) {
                    $queryBuilder
                        ->where("d.deviceid = :devid")
                        ->setParameter('devid', $deviceId);
                }

                $stmt = $queryBuilder->execute();
                return $stmt->fetch();
            }
        );

        $app['datalayer.getalldata.day'] = $app->protect(
            function ($start, $end, $deviceId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('*')
                    ->from('daydata', 'd')
                    ->where('datetime BETWEEN :first AND :last');

                if ($deviceId != null) {
                    $queryBuilder
                        ->andWhere("d.deviceid = :devid")
                        ->setParameter('devid', $deviceId);
                }

                $queryBuilder
                    ->setParameter('first', $start)
                    ->setParameter('last', $end);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll();
            }
        );

        $app['datalayer.getalldata.month'] = $app->protect(
            function ($month, $year, $deviceId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('date', 'kW')
                    ->from('monthdata', 'm')
                    ->where('MONTH(date) = :month AND YEAR(date) = :year');

                if ($deviceId != null) {
                    $queryBuilder
                        ->andWhere("m.deviceid = :devid")
                        ->setParameter('devid', $deviceId);
                }

                $queryBuilder
                    ->setParameter('month', $month)
                    ->setParameter('year', $year);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll();
            }
        );

        $app['datalayer.getdata.day'] = $app->protect(
            function ($date, $deviceId = null) use ($app) {
                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('*')
                    ->from('daydata', 'd')
                    ->where("DATE(datetime) = :date AND NOT kW='0.000'");

                if ($deviceId != null) {
                    $queryBuilder
                        ->andWhere("m.deviceid = :devid")
                        ->setParameter('devid', $deviceId);
                }

                $queryBuilder->setParameter('date', $date);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchAll();
            }
        );

        // Fetch first or last reading of a day
        $app['datalayer.getreading'] = $app->protect(
            function ($date, $type, $deviceId = null) use ($app) {
                if (!in_array($type, array('ASC', 'DESC'))) {
                    throw new \InvalidArgumentException('Invalid type passed to datalayer.getreading');
                }

                /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $app['db']->createQueryBuilder()
                    ->select('datetime')
                    ->from('daydata', 'd')
                    ->where("DATE(datetime) = :date AND kW > 0.000");

                if ($deviceId != null) {
                    $queryBuilder
                        ->andWhere("d.deviceid = :devid")
                        ->setParameter('devid', $deviceId);
                }

                $queryBuilder
                    ->orderBy('datetime', $type)
                    ->setParameter('date', $date);

                $stmt = $queryBuilder->execute();
                return $stmt->fetchColumn();
            }
        );
    }

    public function boot(Application $app)
    {
    }
}