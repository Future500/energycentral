<?php

namespace EC\Service;

use Doctrine\DBAL\Connection;

class Day
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
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
        $queryBuilder = $this->db->createQueryBuilder()
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

    /**
     * @param $start
     * @param $end
     * @param null $deviceId
     * @return mixed
     */
    public function getAllData($start, $end, $deviceId = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
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
} 