<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Training;
use AppBundle\Service\UserService;
use AppBundle\Service\MailerService;
use AppBundle\Service\TrainingService;
use AppBundle\Service\CourseService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrainingController extends Controller
{
    
    /**
    *
    * @Route("/create_training_form", name="create_training_form")
    */
    public function createFormAction(Request $request) 
    {  

        $training = new Training();

        $form = $this->createFormBuilder($training)
            ->setAction($this->generateUrl('create_training'))
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }   

    /**
    *
    * @Route("/create_training", name="create_training")
    */
    public function createAction(Request $request) 
    {   

        $data = $request->request->get('form');
    
        $training = new Training();
        $training->setName($data['name']);
        $training->setDescription($data['description']);
        $training->setCourse($this->get(CourseService::class)->getCourseById(1));

        $response = $this->get(TrainingService::class)->addTraining($training);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Treinamento cadastrado com sucesso. ID ' . $response;
        die;
    }   

}
