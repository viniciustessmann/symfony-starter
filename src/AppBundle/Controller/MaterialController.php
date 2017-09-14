<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use AppBundle\Service\UserService;
use AppBundle\Service\MailerService;
use AppBundle\Service\MaterialService;
use AppBundle\Service\CourseService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MaterialController extends Controller
{
	
	/**
    *
    * @Route("/create_material_form", name="create_material_form")
    */
    public function createFormAction(Request $request) 
    {  

    	$material = new Material();

    	$form = $this->createFormBuilder($material)
    		->setAction($this->generateUrl('create_material'))
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('file', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }	

    /**
    *
    * @Route("/create_material", name="create_material")
    */
    public function createAction(Request $request) 
    {  	

    	$data = $request->request->get('form');
    
    	$material = new Material();
    	$material->setName($data['name']);
    	$material->setDescription($data['description']);
    	$material->setFile($data['file']);
    	$material->setUser($this->get(UserService::class)->getUserById(1));
		$material->setCourse($this->get(CourseService::class)->getCourseById(1));

    	$response = $this->get(MaterialService::class)->addMaterial($material);
    	die;
    }	

}
