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

    public function addState(State $state)
    {
        $this->em->persist($state);
        $this->em->flush();
    }

    public function getStateById($stateId)
    {
        $state = $this->em->getRepository(State::class)->findOneById($stateId);
        
        if (!$state) {
            return false;
        }

        return $state;
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
