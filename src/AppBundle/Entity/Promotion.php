<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Promotion
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Serializer\Groups({"get"})
     */
    private $title;

    /** 
     * @ORM\Column(type="integer") 
     * 
     * @Serializer\Groups({"get"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity="Formation", cascade={"all"}, fetch="EAGER")
     * 
     * @Serializer\Groups({"formations"})
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity="Eleve", mappedBy="promotion", cascade={"persist"})
     * 
     * @Serializer\Groups({"eleves"})
     */
    private $eleves;


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

    public function getAnnee()
    {
        return $this->annee;
    }

    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    public function getFormation()
    {
        return $this->formation;
    }

    public function setFormation($formation)
    {
        $this->formation = $formation;

        return $this;
    }

    public function getEleves()
    {
        return $this->eleves;
    }

    public function setEleves($eleves)
    {
        $this->eleves = $eleves;

        return $this;
    }
}