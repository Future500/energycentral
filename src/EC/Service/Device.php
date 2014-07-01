<?php

namespace EC\Service;

use Doctrine\DBAL\Connection;

class Device
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
     * Adds a new device
     *
     * @param $identifier
     * @param bool $accepted
     * @return string
     */
    public function addNew($identifier, $accepted = false)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->insert('device', 'dev')
            ->values(
                array(
                    'name'     => ':name',
                    'accepted' => ':accepted'
                )
            )
            ->setParameters(
                array(
                    'name'     => $identifier,
                    'accepted' => $accepted
                )
            )
            ->execute();

        return $this->db->lastInsertId();
    }

    /**
     * @param $userId
     * @param $deviceId
     */
    public function addAccess($userId, $deviceId)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->insert('devaccess', 'dev')
            ->values(
                array(
                    'deviceid' => $deviceId,
                    'userid' => $userId
                )
            )
            ->execute();
    }

    /**
     * @param $userId
     * @param $deviceId
     */
    public function revokeAccess($userId, $deviceId)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->delete('devaccess')
            ->where('deviceid = :deviceid AND userid = :userid')
            ->setParameters(
                array(
                    'deviceid' => $deviceId,
                    'userid'   => $userId
                )
            )
            ->execute();
    }

    /**
     * Checks if the user has access to a specific device
     *
     * @param $deviceId
     * @param $userId
     * @return bool
     */
    public function hasAccess($deviceId, $userId)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('ac.deviceid')
            ->from('devaccess', 'ac')
            ->where('userid = :userid')
            ->setParameter('userid', $userId);

        $stmt = $queryBuilder->execute();

        return in_array($deviceId, $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    /**
     * Update the device access table for a specific user.
     * The function adds new devices or removes devices that the user should no longer have access to.
     *
     * @param $userId
     * @param array $addedDevices
     * @param array $removedDevices
     */
    public function updateUserDevices($userId, array $addedDevices, array $removedDevices)
    {
        foreach ($addedDevices as $deviceId) { // add new devices
            $this->addAccess($userId, $deviceId);
        }

        foreach ($removedDevices as $deviceId) { // remove old devices if needed
            $this->revokeAccess($userId, $deviceId);
        }
    }

    /**
     * Update the device access table for a specific device.
     * The function adds new users to a device or removes users from a device
     *
     * @param $deviceId
     * @param array $addedUsers
     * @param array $removedUsers
     */
    public function updateDeviceUsers($deviceId, array $addedUsers, array $removedUsers)
    {
        foreach ($addedUsers as $userId) { // add new users
            $this->addAccess($userId, $deviceId);
        }

        foreach ($removedUsers as $userId) { // remove old users if needed
            $this->revokeAccess($userId, $deviceId);
        }
    }

    /**
     * Updates the accepted status for a specific device
     *
     * @param $deviceId
     * @param bool $accepted
     * @return mixed
     */
    public function setAcceptedStatus($deviceId, $accepted = false)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->update('device', 'dev')
            ->set('accepted', $accepted)
            ->where('deviceid = :deviceid')
            ->setParameter('deviceid', $deviceId);

        return $queryBuilder->execute();
    }

    /**
     * Returns the accepted status for a specific device name
     *
     * @param $deviceName
     * @return bool
     */
    public function getAcceptedStatus($deviceName)
    {
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('accepted')
            ->from('device', 't')
            ->where('t.name = :identifier')
            ->setParameter(':identifier', $deviceName)
            ->execute();

        return $queryBuilder->fetchColumn();
    }

    /**
     * List all users that belong to a specific device ID
     *
     * @param $deviceId
     * @return mixed
     */
    public function getUsers($deviceId)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('u.username')
            ->from('devaccess', 'a')
            ->innerJoin('a', 'user', 'u', 'a.userid = u.userid')
            ->where('a.deviceid = :deviceid')
            ->setParameter('deviceid', $deviceId);

        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Returns a list of all devices, optionally including the identifier
     *
     * @param bool $nameOnly
     * @param null $offset
     * @param null $limit
     * @return mixed
     */
    public function listAll($nameOnly = false, $offset = null, $limit = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select($nameOnly ? 'name' : '*')
            ->from('device', 'dev');

        if ($offset != null || $limit != null) {
            $queryBuilder
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        }

        $stmt = $queryBuilder->execute();
        return $stmt->fetchAll($nameOnly ? \PDO::FETCH_COLUMN : \PDO::FETCH_ASSOC);
    }

    /**
     * Returns the total amount of devices, optionally for a specified user ID
     *
     * @param null $userId
     * @return mixed
     */
    public function count($userId = null)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('COUNT(*)');

        if ($userId != null) {
            $queryBuilder
                ->from('devaccess', 'dev')
                ->where('dev.userid = :userid')
                ->setParameter('userid', $userId);
        } else {
            $queryBuilder->from('device', 'dev');
        }

        $stmt = $queryBuilder->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Extracts the name from a device array
     *
     * @param array $devices
     * @return array
     */
    public function getNames(array $devices)
    {
        array_walk(
            $devices,
            function (&$item) {
                $item = $item['name'];
            }
        );
        return $devices;
    }

    /**
     * Returns the id for every device in the array
     *
     * @param array $devices
     * @return array
     */
    public function getIds(array $devices)
    {
        $deviceIds = array();

        foreach ($devices as $device) {
            /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
            $queryBuilder = $this->db->createQueryBuilder()
                ->select('deviceid')
                ->from('device', 'dev')
                ->where('name = :name')
                ->setParameter('name', $device);

            $stmt = $queryBuilder->execute();
            $deviceIds[$device] = $stmt->fetchColumn();
        }

        return $deviceIds;
    }

    /**
     * Returns the id for a single device
     *
     * @param $deviceName
     * @return string
     */
    public function getSingleId($deviceName)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->db->createQueryBuilder()
            ->select('deviceid')
            ->from('device', 'dev')
            ->where('name = :name')
            ->setParameter('name', $deviceName);

        $stmt = $queryBuilder->execute();

        return $stmt->fetchColumn();
    }
}
