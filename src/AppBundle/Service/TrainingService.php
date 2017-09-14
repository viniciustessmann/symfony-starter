<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\Training;

class TrainingService
{
    protected $em;
    protected $mailer;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getAllTrainings()
    {
        $results =  $this->em->getRepository(Training::class)->findAll();

        $trainings = [];

        foreach ($results as $result) {

            $course = 'Indefinido';

            if ($result->getCourse()) {
                $course = $result->getCourse()->getName();
            }

            $trainings[] = [
                'id' => $result->getId(),
                'name' => $result->getName(),
                'course' => $course
            ];


        }

        return $trainings;
    }

    public function getTrainingById($id)
    {
        $training = $this->em->getRepository(Training::class)->findOneById($id);
        
        if (!$training) {
            return false;
        }

        return $training;
    }

    public function addTraining(Training $training)
    {	

    	try {
    		$this->em->persist($training);
    		$this->em->flush();
    	} catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return $training->getId();
    }

    public function deleteTrainingById($id)
    {

        $training = $this->getTrainingById($id);

        try {
            $this->em->remove($training);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return 'ok';
    }
    public function seachTraining()
    {
      
    }
}

