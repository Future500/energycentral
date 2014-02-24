<?php

namespace EC;

use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Security\Core\Util\SecureRandom;

// use EC\Stats;
// use EC\Application;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new \Kilte\Silex\Pagination\PaginationServiceProvider());

$app->register(
    new SessionServiceProvider(),
    array(
        'session.storage.save_path' => __DIR__.'/../cache/session'
    )
);

$app->register(
    new TwigServiceProvider(),
    array(
        'twig.path'    => array(__DIR__.'/../templates'),
    //   'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
    )
);

$app['twig'] = $app->share(
    $app->extend(
        'twig',
        function ($twig, $app) {
            return $twig;
        }
    )
);

$app['stats'] = $app->share(
    function () {
        return new Stats();
    }
);

$app['datalayer.user'] = $app->protect(
    function ($userId) use ($app) {
        $provider = new Provider\Security\UserProvider($app['db']);
        return $provider->loadUserById($userId);
    }
);

$app['datalayer.users'] = $app->protect(
    function () use ($app) {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $app['db']->createQueryBuilder()
            ->select('*')
            ->from('user', 'u');

        $stmt = $queryBuilder->execute();
        return $stmt->fetchAll();
    }
);

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

$app['datalayer.updatepassword'] = $app->protect(
    function ($userId, $password) use ($app) {
        $randGenerator = new SecureRandom();

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

$app['datalayer.minmax'] = $app->protect(
    function ($type, $deviceId = null) use ($app) {
        if (!in_array($type, array('days', 'months'))) {
            throw new \InvalidArgumentException('Invalid type passed to datalayer.minmax');
        }

        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $app['db']->createQueryBuilder();

        if ($type == 'days') { // Days
            $queryBuilder
                ->select('MIN(datetime) AS minimum', 'MAX(datetime) AS maximum')
                ->from('daydata', 'd');
        } else { // Months
            $queryBuilder
                ->select('MIN(date) AS minimum', 'MAX(date) AS maximum')
                ->from('monthdata', 'd');
        }

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
            ->where('datetime >= :first AND datetime <= :last');

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

$app->register(
    new TranslationServiceProvider(),
    array(
        'locale_fallback' => 'en',
    )
);

$app['translator'] = $app->share(
    $app->extend(
        'translator',
        function ($translator, $app) {
            $translator->addLoader('yaml', new YamlFileLoader());

            $translator->addResource('yaml', __DIR__.'/locales/en.yml', 'en');
            $translator->addResource('yaml', __DIR__.'/locales/nl.yml', 'nl');

            return $translator;
        }
    )
);

return $app;
