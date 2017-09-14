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
}

