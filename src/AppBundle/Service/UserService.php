<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;

class UserService
{
    protected $em;
    protected $mailer;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUserByEmail($email)
    {
        $user = $this->em->getRepository(User::class)->findOneByEmail($email);
        
        if (!$user) {
            return false;
        }

        return $user;
    }

    public function getUserById($id)
    {
        $user = $this->em->getRepository(User::class)->findOneById($id);
        
        if (!$user) {
            return false;
        }

        return $user;
    }

    public function infoUser($id)
    {
        $user = self::getUserById($id);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'User (' . $id . ') not found.'
            ];
        }

        return [
            'id' => $id,
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role' => $user->getRoles()
        ];
    }

    public function registerUser($user)
    {   

        $errors = self::validateForm($user);
        if ($errors) {
            return [
                'error' => true,
                'message' => 'Form has errors',
                'errors' => $errors
            ];
        }

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }
        
        return $user->getId();
    }

    public function editUser($id)
    {   
        $user = self::getUserById($id);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'User (' . $id . ') not found.'
            ];
        }

        $user->setUsername($data->request->get('name') . date('h:i:s'));
        $user->setEmail($data->request->get('email')  . date('h:i:s'));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return $user->getId();
    }

    public function deleteUser($data)
    {
        $id = $data->request->get('id');
        $user = self::getUserById($id);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'User (' . $id . ') not found.'
            ];
        }

        try {
            $this->em->remove($user);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return true;
    }

    public function enableUser($data)
    {   
        $errors = [];
        if ($_GET['token'] === null) {
            $errors[] = 'Token confirmation is required';
        }

        if ($_GET['user'] === null) {
            $errors[] = 'Param id is required';
        }

        $id = $_GET['user'];
        $user = self::getUserById($id);

        if (!$user and isset($id)) {    
            $errors[] = 'User (' . $id . ') not found.';
        }

        if ($_GET['token'] != $user->getConfirmationToken()) {
            $errors[] = 'This token ' . $user->request->get('token') . ' is incorrect.';
        }

        if ($errors) {
            return [
                'error' => true,
                'errors' => $errors
            ];
        }

        $user->setEnabled(true);

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return [
            'success' => true,
            'userId' => $id
        ];
    }

    private function validateForm($data)
    {
        $errors = [];

        if ($data->getUsername() === null) {
            $errors[] = 'Param name is required';
        }

        if ($data->getEmail() === null) {
            $errors[] = 'Param email is required';
        }

        if ($data->getRoles() === null) {
            $errors[] = 'Param role is required';
        }

        //TO DO - verify roles are valid
        // if ($data->request->get('role') != 'TUTOR' && $data->request->get('role') != 'LEARNER') {
        //     $errors[] = 'Use TUTOR or LEARNER to param role';
        // }

        return $errors;
    }
}
