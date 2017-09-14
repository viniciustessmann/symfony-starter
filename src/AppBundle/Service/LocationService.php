<?php

namespace AppBundle\Service;

use AppBundle\Entity\State;
use AppBundle\Entity\City;

class LocationService
{
    protected $em;
    protected $mailer;

    public function __construct($em, $encoder, $mailer)
    {
        $this->em = $em;
    }

    public function getAllStates()
    {
        $results =  $this->em->getRepository(State::class)->findAll();

        $states = [];

        foreach ($results as $result) {
            $states[] = [
                'id' => $result->getId(),
                'name' => $result->getName(),
                'cide' => $result->getCode()
            ];
        }

        return $states;
    }

    public function addState(State $state)
    {   
        try {
            $this->em->persist($state);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return $state->getId();
    }

    public function getStateById($stateId)
    {
        $state = $this->em->getRepository(State::class)->findOneById($stateId);
        
        if (!$state) {
            return false;
        }

        return $state;
    }

    public function deleteStateById($id)
    {

        $state = $this->getStateById($id);

        try {
            $this->em->remove($state);
            $this->em->flush();
        } catch(\Doctrine\DBAL\DBALException $e) {
            
           return [
               'error' => true,
               'message' => $e->getMessage()
           ];
        }

        return true;
    }

    public function getCityById($cityId)
    {
        $city = $this->em->getRepository(City::class)->findOneById($cityId);
        
        if (!$city) {
            return false;
        }

        return $city;
    }

    public function addCity(City $city)
    {   
        $this->em->persist($city);
        $this->em->flush();
    }
}
