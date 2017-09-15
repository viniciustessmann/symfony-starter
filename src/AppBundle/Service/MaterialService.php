<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\Material;

class MaterialService
{
    protected $em;
    protected $mailer;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function addMaterial(Material $material)
    {	
    	try {
    		$this->em->persist($material);
    		$this->em->flush();
    	} catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return $material->getId();
    }

    public function getInfoMaterial($materialId)
    {
        $material = self::getMaterialById($materialId);
        
        if (!$material) {
            return [
                'error' => true,
                'message' => 'material (' . $id . ') not found.'
            ];
        }

        return [
            'id' => $materialId,
            'name' => $material->getName(),
            'date' => $material->getModified(),
            'user' => $material->getUser(),
            'course' => $material->getCourse()
        ];
    }

    public function getMaterialById($materialId)
    {
        $material = $this->em->getRepository(Material::class)->findOneById($materialId);
        
        if (!$material) {
            return false;
        }

        return $material;
    }
}
