<?php

namespace EC\Service;

use Doctrine\DBAL\Connection;

class Month
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
     * @param $month
     * @param $year
     * @param null $deviceId
     * @return mixed
     */
    public function getData($month, $year, $deviceId = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
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