<?php

namespace EC\Service;

use Doctrine\DBAL\Connection;

class Stats
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
     * @param $type
     * @param null $deviceId
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function minMax($type, $deviceId = null)
    {
        if (!in_array($type, array('days', 'months'))) {
            throw new \InvalidArgumentException('Invalid type passed to datalayer.minmax');
        }

        $dateType = ($type == 'days' ? 'datetime' : 'date');
        $table = ($type == 'days' ? 'daydata' : 'monthdata');

        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
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

    /**
     * Returns the first or last reading of a day
     * 
     * @param $date
     * @param $type
     * @param null $deviceId
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getReading($date, $type, $deviceId = null)
    {
        if (!in_array($type, array('ASC', 'DESC'))) {
            throw new \InvalidArgumentException('Invalid type passed to datalayer.getreading');
        }

        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
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
}
