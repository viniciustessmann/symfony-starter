<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\State;
use AppBundle\Entity\City;
use AppBundle\Entity\User;
use AppBundle\Service\LocationService;
use AppBundle\Service\UserService;

class LocationController extends Controller
{   
    /**
    *
    * @Route("/create_state", name="create_state")
    */
    public function createState()
    {   
        $state = new State();
        $state->setName('Rio Grande do Sul');
        $state->setCode('RS');
        $location = $this->get(LocationService::class)->addState($state);

        echo 'OK';
        die;
    }

    /**
    *
    * @Route("/create_city", name="create_city")
    */
    public function createCity()
    {   
        $city = new City();
        $city->setName('Pelotas');
        $city->setState($this->get(LocationService::class)->getStateById(1));
        $location = $this->get(LocationService::class)->addCity($city);

        echo 'OK';
        die;
    }

}
