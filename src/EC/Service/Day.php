<?php

namespace EC\Service;

use EC\Stats\StatsInterface;

class Day
{
    /**
     * @var StatsInterface
     */
    protected $day;

    /**
     * @param StatsInterface $day
     */
    public function __construct(StatsInterface $day)
    {
        $this->day = $day;
    }

    /**
     * @param null $deviceId
     * @param null $date
     * @return array
     */
    public function getDay($deviceId = null, $date = null)
    {
        $dayData = $this->day->fetch($deviceId, $date);

        return $dayData;
    }

    /**
     * Returns the data for a specific day
     *
     * @param $date
     * @param null $deviceId
     * @return mixed
     */
    public function getData($date, $deviceId = null)
    {
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
} 