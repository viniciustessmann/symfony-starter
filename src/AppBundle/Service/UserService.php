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

    private function getUserById($id)
    {
        $user = $this->em->getRepository(User::class)->findOneById($id);
        
        if (!$user) {
            return false;
        }

        return $user;
    }

    public function infoUser($data)
    {
        $id = $data->request->get('id');
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

    public function registerUser($data)
    {   
        $errors = self::validateForm($data);
        if ($errors) {
            return [
                'error' => true,
                'message' => 'Form has errors',
                'errors' => $errors
            ];
        }

        $user = new User();
        $user->setUsername($data->request->get('name') . date('h:i:s'));
        $user->setEmail($data->request->get('email')  . date('h:i:s'));
        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));
        $user->setPassword('123456');
        $user->addRole($data->request->get('role'));

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

    public function editUser($data)
    {
        $id = $data->request->get('id');
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
        if ($data->request->get('token') === null) {
            $errors[] = 'Token confirmation is required';
        }

        if ($data->request->get('id') === null) {
            $errors[] = 'Param id is required';
        }

        $id = $data->request->get('id');
        $user = self::getUserById($id);

        if (!$user and isset($id)) {    
            $errors[] = 'User (' . $id . ') not found.';
        }

        if ($data->request->get('token') != $user->getConfirmationToken()) {
            $errors[] = 'This token ' . $data->request->get('token') . ' is incorrect.';
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

        if ($data->request->get('name') === null) {
            $errors[] = 'Param name is required';
        }

        if ($data->request->get('email') === null) {
            $errors[] = 'Param email is required';
        }

        if ($data->request->get('role') === null) {
            $errors[] = 'Param role is required';
        }

        if ($data->request->get('role') != 'TUTOR' && $data->request->get('role') != 'LEARNER') {
            $errors[] = 'Use TUTOR or LEARNER to param role';
        }

        return $errors;
    }
}
