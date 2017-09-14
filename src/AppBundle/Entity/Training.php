<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Training
 *
 * @ORM\Table(name="training")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrainingRepository")
 */
class Training
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */

     /**
    * @ORM\Column(type="string", length=255)
    */
    protected $name;

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $description;

    /** 
    * @ORM\Column(type="datetime")
    */
    protected $created;


    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="trainings")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    public function __construct()
    {

        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setCreated(new \DateTime());
        if ($this->getModified() == null) {
            $this->setModified(new \DateTime());
        }
    }


    public function getId()
    {
        return $this->id;
    }

     public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->created;
    }

    public function getUser()
    {
        return $this->user;
    }

}

