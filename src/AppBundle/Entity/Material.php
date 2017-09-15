<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Material
 * @ORM\Table(name="material")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MaterialRepository")
 * @Gedmo\Uploadable(pathMethod="getPath", filenameGenerator="SHA1", allowOverwrite=true, maxSize="100000", allowedTypes="image/jpeg,image/pjpeg,image/png,image/x-png")
 
 * 
 */
class Material
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
    * @ORM\Column(type="string", length=255)
    */
    protected $name;

    /**
    * @ORM\Column(name="file", type="string", length=255, nullable=true)
    * @Gedmo\UploadableFilePath
    */
    protected $file;

    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $description;

    /*
    * @ORM\ManyToOne(targetEntity="User", inversedBy="materials")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="Course", inversedBy="materials")
    * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
    */
    private $course;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    public function __construct()
    {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();

        $this->setCreated(new \DateTime());
        if ($this->getModified() == null) {
            $this->setModified(new \DateTime());
        }
    }

    /**
     * Get id
     *
     * @return int
     */
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

    public function setFile($file)
    {
        $this->$file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    public function getCourse()
    {
        return $this->course;

    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getModified()
    {
        return $this->created;
    }

    public function getPath()
    {
        return '/files';
    }
}

