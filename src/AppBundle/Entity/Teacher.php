<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Teacher
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Groups({"get","get_login"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get","get_login"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get","get_login"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"connection"})
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"connection"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"connection","get_login"})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="ModuleTeacher", mappedBy="teachers", cascade={"persist"})
     * 
     * @Serializer\Groups({"modules"})
     */
    private $modules;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->nom = $name;

        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getModule()
    {
        return $this->modules;
    }

    public function setModule($modules)
    {
        $this->modules = $modules;

        return $this;
    }
}