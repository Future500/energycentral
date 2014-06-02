<?php

namespace EC\Service;

use EC\Stats\StatsInterface;

class Month
{
    /**
     * @var StatsInterface
     */
    protected $month;

    /**
     * @param StatsInterface $month
     */
    public function __construct(StatsInterface $month)
    {
        $this->month = $month;
    }

    /**
     * @param null $deviceId
     * @param null $month
     * @param null $year
     * @return array
     */
    public function getMonth($deviceId = null, $month = null, $year = null)
    {
        $monthData = $this->month->fetch($deviceId, $month, $year);

        return $monthData;
    }

    /**
     * @param $month
     * @param $year
     * @param null $deviceId
     * @return mixed
     */
    public function getData($month, $year, $deviceId = null)
    {
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
}