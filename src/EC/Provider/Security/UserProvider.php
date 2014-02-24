<?php

namespace EC\Provider\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use EC\User;
use Doctrine\DBAL\Connection;

class UserProvider implements UserProviderInterface
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function loadUserById($userId)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM user WHERE userid = :userid', array('userid' => $userId));

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('User ID "%i" does not exist.', $username));
        }

        return new User($user['userid'], $user['username'], $user['password'], $user['salt'], explode(',', $user['roles']));
    }

    public function loadUserByUsername($username)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM user WHERE username = :username', array('username' => strtolower($username)));

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return new User($user['userid'], $user['username'], $user['password'], $user['salt'], explode(',', $user['roles']));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}