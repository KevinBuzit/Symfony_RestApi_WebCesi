<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Student
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Groups({"id_student","get_student","get_login"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get_student","get_login"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get_student","get_login"})
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
     * @ORM\ManyToOne(targetEntity="TrainingClass", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="idTrainingClass", referencedColumnName="id")
     * @Serializer\Type("Entity<AppBundle\Entity\TrainingClass>")
     * @Serializer\SerializedName("idTrainingClass")
     * 
     * @Serializer\Groups({"TrainingClass"})
     */
    private $trainingClass;

    /**
     * @ORM\OneToMany(targetEntity="StudentModule", mappedBy="students", cascade={"persist"})
     * 
     * @Serializer\Groups({"marks"})
     */
    private $marks;


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

    public function getClass()
    {
        return $this->trainingClass;
    }

    public function setPromotion($trainingClass)
    {
        $this->trainingClass = $trainingClass;

        return $this;
    }

    public function getMarks()
    {
        return $this->marks;
    }

    public function setMarks($marks)
    {
        $this->marks = $marks;

        return $this;
    }
}