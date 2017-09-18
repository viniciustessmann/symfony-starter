<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\Course;

class CourseService
{
    protected $em;
    protected $mailer;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getAllCourses()
    {
        $results =  $this->em->getRepository(Course::class)->findAll();

        $courses = [];

        foreach ($results as $result) {
            $courses[] = [
                'id' => $result->getId(),
                'name' => $result->getName(),
                'description' => $result->getDescription()
            ];
        }

        return $courses;
    }

    public function getAllCoursesList()
    {
        $results =  $this->em->getRepository(Course::class)->findAll();

        $courses = [];

        foreach ($results as $result) {
            $courses[$result->getName()] = $result->getId();
        }

        return $courses;
    }


    public function getCourseById($id)
    {
        $course = $this->em->getRepository(Course::class)->findOneById($id);
        
        if (!$course) {
            return false;
        }

        return $course;
    }

    public function addCourse($course)
    {       
        //dump($course);die;

        try {
            $this->em->persist($course);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
        
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        return $course->getId();
    }

    public function deleteCourseById($id)
    {

        $course = $this->getCourseById($id);

        try {
            $this->em->remove($course);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return true;
    }

}