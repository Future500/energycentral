<?php

namespace EC\Service;

use Doctrine\DBAL\Connection;

class User
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
     * Returns the amount of users in the user table
     *
     * @return string
     */
    public function count()
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('user', 'u');

        $stmt = $queryBuilder->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Returns all columns for every user in the user table. Can be limited to an (x) amount of rows.
     * When the usernameOnly variable is set to true, only the username for every user will be returned.
     *
     * @param bool $usernameOnly
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    public function getUsers($usernameOnly = false, $offset = null, $limit = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select($usernameOnly ? 'u.username' : '*')
            ->from('user', 'u');

        if ($offset != null && $limit != null) {
            $queryBuilder
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        }

        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll($usernameOnly ? \PDO::FETCH_COLUMN : \PDO::FETCH_ASSOC);
    }

    /**
     * @param array $users
     * @return array
     */
    public function getIds(array $users)
    {
        $userIds = array();

        foreach ($users as $user) {
            /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
            $queryBuilder = $this->db->createQueryBuilder()
                ->select('userid')
                ->from('user', 'u')
                ->where('username = :username')
                ->setParameter('username', $user);

            $stmt = $queryBuilder->execute();
            $userIds[$user] = $stmt->fetchColumn();
        }
        return $userIds;
    }

    /**
     * Return all device IDs a user has access to, optionally including the rest of the row
     *
     * @param bool $withDetails
     * @param null $userId
     * @param null $offset
     * @param null $limit
     * @return array
     */
    public function getDevices($userId, $withDetails = false, $offset = null, $limit = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder();

        if ($withDetails) { // include name and house number?
            $queryBuilder
                ->select('dev.*')
                ->from('devaccess', 'ac')
                ->innerJoin('ac', 'device', 'dev', 'ac.deviceid = dev.deviceid');
        } else {
            $queryBuilder
                ->select('deviceid')
                ->from('devaccess', 'dev');
        }

        if ($offset != null || $limit != null) {
            $queryBuilder
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        }

        $queryBuilder
            ->where('userid = :userid')
            ->setParameter('userid', $userId);

        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll();
    }
}
