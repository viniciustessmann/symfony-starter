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

    public static function registerUser($data)
    {   
       
        $user = new User();
        $user->setUsername($data->request->get('name'));
        $user->setEmail($data->request->get('email'));
        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));
        $user->setPassword('123456');

        //$this->em;
        // $this->em->flush();

        echo 'Enter here to register new user';
    }
}
