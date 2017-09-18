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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\StateType;
use AppBundle\Form\CityType;

class LocationController extends Controller
{   

    /**
    *
    * @Route("/list_state", name="list_state")
    */
    public function listState()
    {   
        $states = $this->get(LocationService::class)->getAllStates();

        return $this->render('state/list.html.twig', array(
            'states' => $states,
        ));
    }

    /**
    *
    * @Route("/create_state_form", name="create_state_form")
    */
    public function createStateForm()
    {   
        $state = new State();

        $form = $this->createForm(StateType::class, $state, ['action' => $this->generateUrl('create_state')]);

        return $this->render('state/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/edit_state_form/{id}", name="edit_state_form",  requirements={"id": "\d+"})
    */
    public function editStateForm($id)
    {   

        $state = $this->get(LocationService::class)->getStateById($id);

        $form = $this->createFormBuilder($state)
            ->setAction($this->generateUrl('create_state'))
            ->add('id', HiddenType::class)
            ->add('name', TextType::class)
            ->add('code', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Atualizar estado'))
            ->getForm();

        return $this->render('state/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/delete_state/{id}", name="delete_state",  requirements={"id": "\d+"})
    */
    public function deleteState($id)
    {   

        $response = $this->get(LocationService::class)->deleteStateById($id);

        if (isset($response['error'])) {

            $this->get('session')->getFlashBag()->add('notice', 'Erroao apagar o estado. ' . $response['message']);
            return $this->redirect('/list_state');
        }

        $this->get('session')->getFlashBag()->add('notice', 'Estado apagado com sucesso');
        return $this->redirect('/list_state');
    }

    /**
    *
    * @Route("/create_state", name="create_state")
    */
    public function createState(Request $request)
    {   
        $data = $request->request->get('state');
        $state = new State();

        if (isset($data['id'])) {
            $state = $this->get(LocationService::class)->getStateById($data['id']);
        }

        $state->setName($data['name']);
        $state->setCode($data['code']);

        $response = $this->get(LocationService::class)->addState($state);

        if (isset($response['error'])) {
            $this->get('session')->getFlashBag()->add('notice', 'Erro ao inserir o estado. ' . $response['message']);
            return $this->redirect('/list_state');
        }

        $this->get('session')->getFlashBag()->add('notice', 'Estado Criado com sucesso');
        return $this->redirect('/list_state');
    }

    /**
    *
    * @Route("/list_city", name="list_city")
    */
    public function listCity()
    {
        $cities = $this->get(LocationService::class)->getAllCities();

        return $this->render('city/list.html.twig', array(
            'cities' => $cities,
        ));
    }

    /**
    *
    * @Route("/create_city_form", name="create_city_form")
    */
    public function createFormCity()
    {   
        $city = new City();
        $states = $this->get(LocationService::class)->getAllStatesList();
        $form = $this->createForm(CityType::class, $city, ['action' => $this->generateUrl('create_city'), 'states' => $states]);

        return $this->render('city/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/edit_city_form/{id}", name="edit_city_form",  requirements={"id": "\d+"})
    */
    public function editCityForm($id)
    {   

        $city = $this->get(LocationService::class)->getCityById($id);

        $states = $this->get(LocationService::class)->getAllStatesList();

        $form = $this->createFormBuilder($city)
            ->setAction($this->generateUrl('create_city'))
            ->add('id', HiddenType::class)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Atualizar cidade'))
            ->getForm();

        return $this->render('state/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/create_city", name="create_city")
    */
    public function createCity(Request $request)
    {   

        $data = $request->request->get('city');

        $city = new City();

        if (isset($data['id'])) {
            $city = $this->get(LocationService::class)->getCityById($data['id']);
        }

        $city->setName($data['name']);
        $city->setState($this->get(LocationService::class)->getStateById($data['state']));

        $response = $this->get(LocationService::class)->addCity($city);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        $this->get('session')->getFlashBag()->add('notice', 'Cidade ' . $data['name'] .' cadastrada com sucesso.');
        return $this->redirect('/list_city');
    }

    /**
    *
    * @Route("/delete_city/{id}", name="delete_city",  requirements={"id": "\d+"})
    */
    public function deleteCity($id)
    {   

        $response = $this->get(LocationService::class)->deleteCityById($id);

        if (isset($response['error'])) {
            $this->get('session')->getFlashBag()->add('notice', 'Erro ao apagar cidade. ' . $response['message']);
            return $this->redirect('/list_city');
        }

        $this->get('session')->getFlashBag()->add('notice', 'Cidade apagada com sucesso.');
        return $this->redirect('/list_city');
    }

}
