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
    * @ORM\Column(type="text")
    */
    protected $description;


    /** 
    * @ORM\Column(type="datetime", nullable=true)
    */
    protected $starter;

    /** 
    * @ORM\Column(type="datetime")
    */
    protected $created;


    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="trainings")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
    * @ORM\ManyToMany(targetEntity="User")
    * @ORM\JoinTable(name="users_trainings",
    *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="use_id", referencedColumnName="id" , onDelete="CASCADE")}
    *      )
    */
    private $users;

    public function __construct()
    {

        $this->users = new \Doctrine\Common\Collections\ArrayCollection();

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


    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->created;
    }

    public function setUsers(User $user)
    {
        $this->users->add($user);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setStarter($starter)
    {
        $this->starter = $starter;
    }

    public function getStarter()
    {
        return $this->starter;
    }
}

