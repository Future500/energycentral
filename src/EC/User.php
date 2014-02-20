<?php

namespace EC;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    protected $id;
    protected $username;
    protected $password;
    protected $roles;
    protected $salt;

    public function __construct($userid, $username, $password, array $roles = array())
    {
        $this->id = $userid;
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRoles()
    {
        return $this->roles == null ? array('ROLE_USER') : $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function eraseCredentials()
    {
        // ...
    }
}