<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

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

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
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

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/material';
    }
}

