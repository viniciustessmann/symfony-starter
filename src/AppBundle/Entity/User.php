<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToMany(targetEntity="Training")
     * @ORM\JoinTable(name="users_courses",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="training_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $trainings;


    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="users")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
         /**
         * @ORM\ManyToOne(targetEntity="State", inversedBy="users")
         * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
         */
        private $state;

    public function __construct()
    {
        parent::__construct();

        $this->trainings = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setCreated(new \DateTime());
        if ($this->getModified() == null) {
            $this->setModified(new \DateTime());
        }
    }

    public function setName($name)
    {   
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->created;
    }

    public function addTrainings($training)
    {   
        $this->trainings->add($training);
    }
    
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState(State $state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

}