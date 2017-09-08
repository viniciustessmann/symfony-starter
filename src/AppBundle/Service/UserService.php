<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;

class UserService
{
    protected $em;
    
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function registerUser($data)
    {   
        $user = new User();
        $user->setUsername($data->request->get('name') . date('h:i:s'));
        $user->setEmail($data->request->get('email')  . date('h:i:s'));
        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));
        $user->setPassword('123456');

        $this->em->persist($user);
        $this->em->flush();
        
        return $user->getId();
    }
}
