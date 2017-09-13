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
        try {
            $this->em->persist($course);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
        
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        return 'OK';
    }

}