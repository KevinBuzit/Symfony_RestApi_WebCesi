<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Module
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Groups({"id_module","get_module"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get_module"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get_module"})
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="TrainingModule", mappedBy="module", cascade={"persist"})
     * 
     * @Serializer\Groups({"training"})
     */
    private $training;

    /**
     * @ORM\OneToMany(targetEntity="ModuleTeacher", mappedBy="modules", cascade={"persist"})
     * 
     * @Serializer\Groups({"teachers"})
     */
    private $teachers;

    /**
     * @ORM\OneToMany(targetEntity="StudentModule", mappedBy="module", cascade={"persist"})
     * 
     * @Serializer\Groups({"marks"})
     */
    private $marks;


    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getTraining()
    {
        return $this->training;
    }

    public function setTraining($training)
    {
        $this->training = $training;

        return $this;
    }

    public function getTeacher()
    {
        return $this->teachers;
    }

    public function setTeacher($teachers)
    {
        $this->teachers = $teachers;

        return $this;
    }
}